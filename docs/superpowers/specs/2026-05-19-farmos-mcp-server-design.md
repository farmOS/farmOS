# farmOS MCP Server — v1 Design

**Date:** 2026-05-19
**Status:** Approved (defaults applied), ready for `writing-plans`
**Scope:** New `mcp-server/` directory inside `agriforestryOS` repo. No upstream contribution.

## Problem

AgriforestryOS has a domain-specific data model (Tree with 13 custom fields, Infrastructure with a condition enum, syntropic taxonomy vocabularies) exposed through a standards-compliant JSON:API. The API works, but every query requires constructing complex filter expressions by hand. Claude needs intention-revealing tools so the user can ask farm questions conversationally without ad-hoc API construction every session.

Per the "tight schema first" priority, v1 ships **read-only**. Writes land in v1.1 after read tools are validated against real Goldberry data.

## Goals

- Let Claude answer questions like "how many American Chestnuts do we have?" without manual filter construction.
- Expose curated, domain-specific tools that map directly to AgriforestryOS concepts (species, DBH, stratum, condition).
- Surface farmOS validation errors back to Claude in plain language so it can self-correct.
- Live inside `agriforestryOS` so schema changes (`Tree.php`) and tool updates land in the same commit.
- Support both local dev (basic_auth, `http://localhost`) and a future remote instance (OAuth2) without a config change on Claude's side.
- Test the server against fixture JSON snapshots so CI doesn't need a live farmOS.

## Non-Goals

- No generic "run any JSON:API filter" escape hatch — defeats curation.
- No direct PostgreSQL access — go through JSON:API so farmOS access control is always enforced.
- No Drupal module — the MCP server is a standalone process.
- No write operations in v1 (deferred to v1.1).
- No OAuth2 device flow in v1 — env-var basic_auth is sufficient for local self-hosted use.
- No upstream PRs to farmOS or any dependency.
- No geospatial query tool in v1 — deferred until QGIS work begins.
- No log/observation tool in v1 — farmOS Logs are Phase 2 scope.

## Design

### Repo Layout

```
agriforestryOS/
  mcp-server/
    pyproject.toml          # project metadata, deps, entry point
    server.py               # all tools (v1)
    client.py               # thin httpx wrapper: auth, base URL, JSON:API helpers
    tests/
      __init__.py
      conftest.py           # pytest-httpx fixtures
      test_tools.py         # one test class per tool
      fixtures/
        asset_types.json
        trees_collection.json
        trees_filtered_by_species.json
        infrastructure_needs_repair.json
        tree_single.json
        validation_error_dbh_too_high.json
    README.md
  .github/workflows/
    mcp-server-ci.yml       # new workflow (added in this PR)
```

`pyproject.toml`:
- `[project]` name: `agriforestryos-mcp`, version `0.1.0`, Python `>=3.11`
- Runtime deps: `fastmcp>=2.0`, `httpx>=0.27`
- Dev deps: `pytest`, `pytest-httpx`, `pytest-asyncio`
- `[project.scripts] agriforestryos-mcp = "server:mcp.run"` (or equivalent FastMCP entry)

### Authentication

Server reads credentials from environment variables at startup:

| Var | Required | Default | Notes |
|---|---|---|---|
| `FARMOS_BASE_URL` | Yes | — | e.g. `http://localhost` |
| `FARMOS_AUTH_MODE` | No | `basic` | `basic` in v1; `oauth2` reserved for v1.2+ |
| `FARMOS_USERNAME` | If `basic` | — | |
| `FARMOS_PASSWORD` | If `basic` | — | |

Claude Code registers the server in `~/.claude/settings.json` under `mcpServers` with the `env` block passing these values. For local dev, an `mcp-server/.env` file (gitignored — added to `.gitignore` in this PR) is loaded via `uv run --env-file .env server.py`.

`client.py` injects `Authorization: Basic <b64(user:pass)>` on every request and raises a clear startup error if required vars are missing.

### Tool Surface (v1 — 5 tools, all read-only)

All tools defined in `server.py` using FastMCP's `@mcp.tool()` decorator.

---

**`list_asset_types()`**
- Parameters: none
- Returns: `list[dict]` of `{id: str, label: str}` for all registered asset bundles
- Method: `GET /jsonapi/asset_type/asset_type`
- Purpose: discoverability — Claude enumerates what kinds of assets exist before issuing queries

---

**`count_trees(species: str | None = None, health_status: str | None = None) -> dict`**
- Parameters:
  - `species` (optional): common name or `plant_type` taxonomy term label (e.g., `"American Chestnut"`)
  - `health_status` (optional): label of a `tree_health` taxonomy term (e.g., `"Good"`)
- Returns: `{count: int, filters_applied: dict}`
- Method: `GET /jsonapi/asset/tree` with `filter[species.name]=<species>` and/or `filter[health_status.name]=<status>` if provided. Use `page[limit]=0` to read `meta.count`; if the server doesn't return `meta.count`, fall back to `fields[asset--tree]=drupal_internal__id&page[limit]=1000` and count client-side.
- Purpose: first-line sanity check ("how many American Chestnuts?")

---

**`query_trees(species: str | None = None, stratum: str | None = None, succession_stage: str | None = None, health_status: str | None = None, min_dbh_cm: float | None = None, max_dbh_cm: float | None = None, fields: list[str] | None = None, limit: int = 50) -> list[dict]`**
- Parameters: all optional filters; `fields` is the sparse-fieldset list; `limit` defaults to 50, hard-capped at 500
- Returns: list of tree records with requested fields resolved
- Default fields if `fields` is `None`: `["id", "name", "species", "dbh_cm", "height_m", "stratum", "health_status", "planting_date"]`
- Method: `GET /jsonapi/asset/tree?include=species,stratum,succession_stage,health_status&filter[...]&fields[asset--tree]=...&page[limit]=...`
- Purpose: workhorse — "What's the average DBH of chestnuts in the high canopy?" Claude calls this, computes the average from the response.

---

**`get_tree(id: str) -> dict`**
- Parameters: `id` (UUID string)
- Returns: full tree record with all 13 custom fields, plus resolved taxonomy reference labels
- Method: `GET /jsonapi/asset/tree/<uuid>?include=species,stratum,succession_stage,health_status`
- Purpose: single-asset deep inspection

---

**`list_infrastructure(condition: str | None = None) -> list[dict]`**
- Parameters: `condition` (optional) — one of `new | good | fair | needs_repair | decommissioned`
- Returns: list of `{id, name, infrastructure_type, condition, material, installation_date}`
- Method: `GET /jsonapi/asset/infrastructure?filter[condition]=needs_repair&include=infrastructure_type` (filter omitted if `condition` is `None`)
- Purpose: direct answer to "List all infrastructure that needs repair"

---

**Deferred to v1.1: `create_tree(...)`.** Designed and documented in the v1.1 plan; not implemented in v1. See "Deferred Tools" below.

### Error Handling

`client.py` wraps every `httpx.AsyncClient` call:

- **HTTP 4xx with `application/vnd.api+json` body:** parse the `errors` array. Each error's `{title, detail, source.pointer}` is concatenated into a single human-readable string. Example:
  > `"farmOS rejected the request: dbh_cm must be between 0 and 1000 (got 1500) [source: /data/attributes/dbh_cm]"`
- **HTTP 4xx with non-JSON:API body:** wrap as `"farmOS returned HTTP <code>: <body[:200]>"`.
- **HTTP 5xx and network errors:** `"farmOS is unreachable: <message>"`.
- All errors propagate as `ToolError` (FastMCP's `is_error=True` mechanism), so Claude receives them as tool output, not as a transport crash.

This is the primary expected error case once write tools land in v1.1 (Tree's `dbh_cm` range + required `species`).

### Testing

`pytest-httpx` patches `httpx.AsyncClient` so tests run without a live farmOS. Fixture JSON files in `tests/fixtures/` are captured from the smoke-test stack (one-time `curl` run; fixtures are committed).

Test matrix (one test per tool path):

| Test | What it asserts |
|---|---|
| `test_list_asset_types` | Returns the expected bundle list shape from fixture |
| `test_count_trees_no_filter` | Correct URL, parses `meta.count` |
| `test_count_trees_by_species` | Correct `filter[species.name]` encoding |
| `test_query_trees_default_fields` | Correct `fields[asset--tree]` and default field list |
| `test_query_trees_with_dbh_range` | Correct `filter[dbh_cm][operator]=BETWEEN` (or equivalent) |
| `test_query_trees_sparse_fields_override` | User-provided `fields` overrides default |
| `test_query_trees_limit_caps_at_500` | `limit=999` clamped to `500` |
| `test_get_tree_by_uuid` | Correct path interpolation, includes resolved |
| `test_get_tree_404` | Missing tree raises `ToolError` with readable message |
| `test_list_infrastructure_no_filter` | Returns all infrastructure |
| `test_list_infrastructure_needs_repair` | Correct condition filter |
| `test_client_validation_error_surfaces_plain_text` | 422 with JSON:API errors becomes a plain-language string |
| `test_client_unreachable_farmos` | Network error becomes a clean ToolError |

Coverage target: every tool has at least one happy-path test and one error-path test.

### CI Workflow

New file `.github/workflows/mcp-server-ci.yml`:

```yaml
name: MCP server CI
on:
  pull_request:
    branches: [4.x, 'feature/**']
    paths:
      - 'mcp-server/**'
      - '.github/workflows/mcp-server-ci.yml'
  push:
    branches: [4.x]
    paths:
      - 'mcp-server/**'

permissions:
  contents: read

concurrency:
  group: mcp-server-${{ github.workflow }}-${{ github.ref }}
  cancel-in-progress: true

jobs:
  test:
    runs-on: ubuntu-latest
    timeout-minutes: 5
    steps:
      - uses: actions/checkout@v4
      - uses: astral-sh/setup-uv@<SHA>          # determine current pinned SHA in plan
        with:
          enable-cache: true
      - run: uv sync --project mcp-server
      - run: uv run --project mcp-server pytest mcp-server/tests/ -v
```

The new workflow file must be added to the `actionlint` job's explicit file list in `.github/workflows/ci-farm-syntropic.yml`.

### Documentation

`mcp-server/README.md` covers:

1. **Prerequisites:** `uv`, Python 3.11+
2. **Install:** `claude mcp add agriforestryos-mcp -- uv run --project /path/to/agriforestryOS/mcp-server agriforestryos-mcp` plus the env vars
3. **Environment variables:** table with the 4 vars
4. **Tool reference:** one section per tool with parameters, return shape, and a one-line "when to use"
5. **Example session transcript:** plain-text dialogue showing Claude calling each of the 5 tools against a populated farm. Realistic data, not synthetic.
6. **v1.1 preview:** note that `create_tree` is coming, with consent-flow notes

## Decisions Made

| Question | Decision | Rationale |
|---|---|---|
| Python vs Node | **Python FastMCP** | Better JSON:API ergonomics, matches broader stack language, `uv` already in use |
| Write tools in v1? | **No — v1.1** | "Tight schema first" priority. Validate reads against real Goldberry data before exposing writes. |
| Geospatial query tool in v1? | **No — Phase 2** | Defer until QGIS integration begins; JSON:API filter for geometry is non-standard and worth a separate design pass |
| Logs tool in v1? | **No — Phase 2** | farmOS Logs are explicitly Phase 2 scope per project memory |
| Repo location | **Inside `agriforestryOS`** as `mcp-server/` | Schema + tool changes ship in one commit. If MCP later needs independent release cadence, easy to extract. |
| Auth model | **Env-var basic_auth in v1, OAuth2 reserved** | Local self-hosted use is the only audience today. Cloudflare tunnel + basic_auth over HTTPS is a viable interim step if the instance goes remote. |

## Open Questions for Implementation Phase

These are *not* design questions — they're for the plan phase:

1. **Exact FastMCP version** to pin. Plan should check current stable, pin SHA-equivalent in `pyproject.toml`.
2. **`astral-sh/setup-uv` action version** for CI. Plan pins to a SHA.
3. **`page[limit]=0` behavior** — does farmOS JSON:API actually return `meta.count` for `limit=0` queries? If not, the `count_trees` tool falls back to client-side counting. Verify against the smoke-test stack early.
4. **Filter syntax for `min_dbh_cm`/`max_dbh_cm`** — JSON:API allows `filter[dbh_cm][value]=N&filter[dbh_cm][operator]=>=` but Drupal's JSON:API extension has its own syntax for range filters. Confirm during plan.
5. **Should `mcp-server/.env` be in this PR's `.gitignore`** or already covered by a root `.env` rule? Audit `.gitignore` during plan.

## Deferred Tools (v1.1 and beyond)

| Tool | Target version | Reason for deferral |
|---|---|---|
| `create_tree(...)` | v1.1 | Validate reads first |
| `update_tree(id, fields)` | v1.1 | Same as create |
| `archive_tree(id)` | v1.1 | Wraps farmOS's archive flag |
| `geospatial_query_assets(within_polygon_wkt)` | Phase 2 | Pairs with QGIS work |
| `list_logs(asset_id)` | Phase 2 | farmOS Logs are Phase 2 |
| `create_observation_log(asset_id, ...)` | Phase 2 | Pairs with logs |
| OAuth2 device flow | v1.2 | When the instance goes remote |

## Files Touched (this PR)

- `mcp-server/pyproject.toml` — new
- `mcp-server/server.py` — new
- `mcp-server/client.py` — new
- `mcp-server/tests/conftest.py` — new
- `mcp-server/tests/test_tools.py` — new
- `mcp-server/tests/fixtures/*.json` — new (5–6 fixtures)
- `mcp-server/README.md` — new
- `.github/workflows/mcp-server-ci.yml` — new
- `.github/workflows/ci-farm-syntropic.yml` — add new workflow to actionlint's file list
- `.gitignore` — add `mcp-server/.env` if not already covered
- `CHANGELOG.md` — Unreleased: Added (MCP server v1, 5 read-only tools)

## CI Impact

- `farm_syntropic CI (fast) / Workflow lint (actionlint)` — passes once the new workflow is added to the explicit file list
- `MCP server CI / test` — new gating check; should pass since all tests are mocked
- `farm_syntropic smoke test` — unchanged (MCP server doesn't touch farmOS module code)
- `farm_syntropic PHPStan` — unchanged

## Future-Looking Notes

- The 5-tool surface is intentionally lean. When v1.1 adds `create_tree`, the implementation pattern is established and each subsequent write tool is ~20–30 lines.
- When QGIS work begins, `geospatial_query_assets` becomes the bridge: Claude can ask "which trees fall inside this polygon?" by passing WKT computed from QGIS layer geometry.
- When farmOS goes remote, the `FARMOS_AUTH_MODE=oauth2` path becomes implementable. Today's design keeps the door open with a single env-var switch.
