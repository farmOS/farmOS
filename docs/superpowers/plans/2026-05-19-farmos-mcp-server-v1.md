# farmOS MCP Server v1 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a read-only FastMCP server in `mcp-server/` that exposes five curated JSON:API tools against AgriforestryOS so Claude can answer farm questions conversationally without constructing raw API filters.

**Architecture:** Single-file FastMCP server (`server.py`) backed by a thin httpx wrapper (`client.py`) that injects basic-auth and parses JSON:API error envelopes into plain-language strings; all five tools are read-only and decorated with `@mcp.tool()`; env-var validation at startup fails loudly if required credentials are absent.

**Tech Stack:** Python 3.11+, FastMCP 3.3.x, httpx 0.27.x, pytest, pytest-httpx, pytest-asyncio, uv (no pip).

---

## Resolved Open Questions

### 1. FastMCP version pin: `fastmcp>=3.3,<4`

As of 2026-05-19 the current stable is 3.3.x. Floor `>=3.3` is chosen for the slim packaging split (server vs. client distribution); ceiling `<4` guards against a likely-breaking 4.0. Implementation worker should verify against `pip index versions fastmcp` at impl time.

### 2. `astral-sh/setup-uv` action SHA pin

Plan instructs the worker to verify the current latest at impl time:
```
gh release list --repo astral-sh/setup-uv --limit 5
gh release view <latest-tag> --repo astral-sh/setup-uv --json tagName,targetCommitish
```
and pin to that SHA with a trailing version comment. As of 2026-05-19 a reasonable starting point is v6.1.0 SHA `f0ec1fc3b38f5e7cd731bb6ce540c5af426746bb`, but the worker must reconfirm before committing the workflow YAML.

### 3. `page[limit]=0` behavior — probe before coding `count_trees`

The Drupal JSON:API does not guarantee `meta.count` for `page[limit]=0`. Task 3 probes the live smoke-test stack to determine which code path is primary. Both paths are implemented (Task 10) regardless.

### 4. JSON:API range filter syntax for `min_dbh_cm` / `max_dbh_cm`

Standard Drupal JSON:API range syntax is two-condition `>=` + `<=`. Task 3 probes whether this is accepted or whether BETWEEN is required. Both are documented; Task 11 uses whichever was confirmed.

### 5. Root `.gitignore` coverage of `mcp-server/.env`

The bare `.env` pattern in root `.gitignore` covers `mcp-server/.env` (git applies patternless paths to any depth). No root change needed. A `mcp-server/.gitignore` with `.env` is created as belt-and-suspenders.

---

## File Structure

| Action | File | Purpose |
|---|---|---|
| Create | `mcp-server/pyproject.toml` | Project metadata + pinned deps + script entry |
| Create | `mcp-server/server.py` | FastMCP entry + 5 tool definitions |
| Create | `mcp-server/client.py` | httpx wrapper with auth + JSON:API error parsing |
| Create | `mcp-server/.gitignore` | `.env` belt-and-suspenders |
| Create | `mcp-server/README.md` | Prerequisites, install, env vars, tool reference, example session |
| Create | `mcp-server/tests/__init__.py` | Empty marker |
| Create | `mcp-server/tests/conftest.py` | pytest fixtures (FIXTURES_DIR, load_fixture, farm_client) |
| Create | `mcp-server/tests/test_tools.py` | 13+ tests, all mocked via pytest-httpx |
| Create | `mcp-server/tests/fixtures/*.json` | 6 captured JSON:API response snapshots |
| Create | `.github/workflows/mcp-server-ci.yml` | Advisory CI workflow — pytest with pinned uv |
| Modify | `.github/workflows/ci-farm-syntropic.yml` | Add new workflow to actionlint's explicit file list |
| Modify | `CHANGELOG.md` | Unreleased: Added entries |

---

## Tasks

### Task 1 — Create `mcp-server/pyproject.toml` with pinned versions

- [ ] Create directory `mcp-server/`.
- [ ] Create `mcp-server/pyproject.toml`:
  ```toml
  [project]
  name = "agriforestryos-mcp"
  version = "0.1.0"
  description = "Read-only MCP server exposing AgriforestryOS farm data to Claude"
  readme = "README.md"
  requires-python = ">=3.11"
  dependencies = [
      "fastmcp>=3.3,<4",
      "httpx>=0.27,<1",
  ]

  [project.scripts]
  agriforestryos-mcp = "server:main"

  [build-system]
  requires = ["hatchling"]
  build-backend = "hatchling.build"

  [tool.hatch.build.targets.wheel]
  include = ["server.py", "client.py"]

  [tool.uv]
  dev-dependencies = [
      "pytest>=8.2,<9",
      "pytest-httpx>=0.30,<1",
      "pytest-asyncio>=0.23,<1",
  ]

  [tool.pytest.ini_options]
  asyncio_mode = "auto"
  testpaths = ["tests"]
  ```

  Pin rationale: see Resolved Open Questions §1. `[project.scripts]` points to `server:main` — a module-level callable wrapping `mcp.run()`, NOT the bound `mcp.run` method (entry points must resolve to plain callables).

### Task 2 — Create `mcp-server/.gitignore`

- [ ] Create `mcp-server/.gitignore` with exactly one line:
  ```
  .env
  ```
- [ ] Verify root `.gitignore` already covers it:
  ```
  git check-ignore -v mcp-server/.env
  ```
  Expected: `.gitignore:9:.env    mcp-server/.env` (or similar referencing the `.env` rule). If NOT ignored, add `mcp-server/.env` to root `.gitignore` explicitly.

### Task 3 — Probe live farmOS: `page[limit]=0` and range filter syntax

Boot the smoke-test stack (same shape as `.github/workflows/smoke-farm-syntropic.yml`), then:

- [ ] **Probe A** — `page[limit]=0` returns `meta.count`?
  ```bash
  curl -fsS -u admin:admin \
    -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset/tree?page[limit]=0' \
    | python3 -c "import json,sys; d=json.load(sys.stdin); print('meta.count present:', 'count' in d.get('meta',{}))"
  ```
  Document the result. If YES: `count_trees` primary path uses `page[limit]=0`. If NO: fallback to a paginated count loop is the primary.

- [ ] **Probe B** — range filter syntax. First create a second tree:
  ```bash
  docker compose -f docker-compose.smoke.yml exec -T www drush php:eval "
    \$a = \Drupal::entityTypeManager()->getStorage('asset')->create([
      'type'=>'tree','name'=>'Probe tree 2','dbh_cm'=>'25.0'
    ]);
    \$a->save();
    print 'created id='.\$a->id();
  "
  ```
  Then probe two-condition filter:
  ```bash
  curl -fsS -u admin:admin \
    -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset/tree?filter[dbh-min][path]=dbh_cm&filter[dbh-min][value]=5&filter[dbh-min][operator]=%3E%3D&filter[dbh-max][path]=dbh_cm&filter[dbh-max][value]=50&filter[dbh-max][operator]=%3C%3D' \
    | python3 -c "import json,sys; d=json.load(sys.stdin); print('data count:', len(d.get('data',[])))"
  ```
  Expected: `data count: 2`. If 400 or 0, probe BETWEEN as alternative.

- [ ] Document the working syntax in a comment at the top of `query_trees` (Task 11).

### Task 4 — Capture fixture JSON files

With the smoke stack running and both probe trees created, capture 6 fixtures. Use `python3 -m json.tool` for deterministic formatting.

- [ ] **4a — `asset_types.json`:**
  ```bash
  curl -fsS -u admin:admin -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset_type/asset_type' \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/asset_types.json
  ```

- [ ] **4b — `trees_collection.json`:**
  ```bash
  curl -fsS -u admin:admin -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset/tree?include=species,stratum,succession_stage,health_status' \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/trees_collection.json
  ```

- [ ] **4c — `trees_filtered_by_species.json`:** Create a species term + assign it first if needed, then:
  ```bash
  curl -fsS -u admin:admin -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset/tree?filter[species.name]=American+Chestnut&include=species' \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/trees_filtered_by_species.json
  ```

- [ ] **4d — `tree_single.json`:** Use first tree UUID from 4b:
  ```bash
  UUID=$(python3 -c "import json; d=json.load(open('mcp-server/tests/fixtures/trees_collection.json')); print(d['data'][0]['id'])")
  curl -fsS -u admin:admin -H 'Accept: application/vnd.api+json' \
    "http://localhost/jsonapi/asset/tree/${UUID}?include=species,stratum,succession_stage,health_status" \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/tree_single.json
  ```

- [ ] **4e — `infrastructure_needs_repair.json`:** Create a fixture infra asset first, then:
  ```bash
  docker compose -f docker-compose.smoke.yml exec -T www drush php:eval "
    \$a = \Drupal::entityTypeManager()->getStorage('asset')->create([
      'type'=>'infrastructure','name'=>'Test Fence','condition'=>'needs_repair'
    ]);
    \$a->save();
  "
  curl -fsS -u admin:admin -H 'Accept: application/vnd.api+json' \
    'http://localhost/jsonapi/asset/infrastructure?filter[condition]=needs_repair&include=infrastructure_type' \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/infrastructure_needs_repair.json
  ```

- [ ] **4f — `validation_error_dbh_too_high.json`:** Capture a 422 (used for client error-parsing tests; server has no write tools):
  ```bash
  curl -sS -u admin:admin -X POST \
    -H 'Accept: application/vnd.api+json' \
    -H 'Content-Type: application/vnd.api+json' \
    -d '{"data":{"type":"asset--tree","attributes":{"name":"Bad Tree","dbh_cm":9999}}}' \
    'http://localhost/jsonapi/asset/tree' \
    | python3 -m json.tool \
    > mcp-server/tests/fixtures/validation_error_dbh_too_high.json
  ```
  Verify the file contains `"errors"` at the top level. Confirm `errors[0]` has `title`, `detail`, `source.pointer`.

- [ ] Sanity check: no real credentials in fixtures.
  ```bash
  grep -r "password\|secret\|token" mcp-server/tests/fixtures/ || echo "clean"
  ```

### Task 5 — Implement `mcp-server/client.py`

- [ ] Create `mcp-server/client.py` with a `FarmOSClient` class that:
  1. Accepts `base_url`, `username`, `password` in `__init__`. Raises `ValueError` if any is empty.
  2. Exposes `async def get(self, path: str, params: dict | None = None) -> dict`. Uses `httpx.AsyncClient` with `BasicAuth` and `Accept: application/vnd.api+json` header. Use `async with httpx.AsyncClient(...) as client:` per call.
  3. **Error handling:**
     - `httpx.ConnectError` / `httpx.TimeoutException` / `httpx.TransportError`: raise `ToolError(f"farmOS is unreachable: {exc}")`
     - 4xx with `application/vnd.api+json` body: parse `response.json()["errors"]`, concatenate each error's `{title, detail, source.pointer}` joined with `"; "`, raise `ToolError(f"farmOS rejected the request: {msg}")`
     - 4xx without JSON:API content type: raise `ToolError(f"farmOS returned HTTP {response.status_code}: {response.text[:200]}")`
     - 5xx: same as transport error pattern
  4. Expose `async def get_single(self, path: str, params: dict | None = None) -> dict` — returns `response_json["data"]`.
  5. Import `ToolError` from `fastmcp.exceptions`. Verify the import path:
     ```bash
     uv run --project mcp-server python3 -c "from fastmcp.exceptions import ToolError; print('ok')"
     ```

### Task 6 — Write failing tests for client error handling

- [ ] Create `mcp-server/tests/__init__.py` (empty).
- [ ] Create `mcp-server/tests/conftest.py`:
  ```python
  from pathlib import Path
  import json
  import pytest
  from client import FarmOSClient

  FIXTURES_DIR = Path(__file__).parent / "fixtures"

  @pytest.fixture
  def load_fixture():
      def _load(name: str) -> dict:
          return json.loads((FIXTURES_DIR / name).read_text())
      return _load

  @pytest.fixture
  def farm_client():
      return FarmOSClient("http://farmos.local", "admin", "admin")
  ```

- [ ] Create `mcp-server/tests/test_tools.py` with the two client error tests:
  ```python
  import pytest
  import httpx
  from pytest_httpx import HTTPXMock
  from client import FarmOSClient
  from fastmcp.exceptions import ToolError

  @pytest.mark.asyncio
  async def test_client_validation_error_surfaces_plain_text(httpx_mock: HTTPXMock, load_fixture):
      fixture = load_fixture("validation_error_dbh_too_high.json")
      httpx_mock.add_response(
          method="GET",
          url="http://farmos.local/jsonapi/asset/tree",
          status_code=422,
          headers={"Content-Type": "application/vnd.api+json"},
          json=fixture,
      )
      client = FarmOSClient("http://farmos.local", "admin", "admin")
      with pytest.raises(ToolError) as exc_info:
          await client.get("/jsonapi/asset/tree")
      assert "farmOS rejected the request" in str(exc_info.value)
      assert "dbh_cm" in str(exc_info.value)

  @pytest.mark.asyncio
  async def test_client_unreachable_farmos(httpx_mock: HTTPXMock):
      httpx_mock.add_exception(httpx.ConnectError("Connection refused"))
      client = FarmOSClient("http://farmos.local", "admin", "admin")
      with pytest.raises(ToolError) as exc_info:
          await client.get("/jsonapi/asset/tree")
      assert "farmOS is unreachable" in str(exc_info.value)
  ```

- [ ] Run: `uv run --project mcp-server pytest mcp-server/tests/test_tools.py -v`. Expected: FAIL (no client.py yet).

### Task 7 — Implement `client.py` and confirm Task 6 tests PASS

- [ ] Write `client.py` per Task 5 spec.
- [ ] Run tests again: `uv run --project mcp-server pytest mcp-server/tests/ -v`. Expected: 2 passed.

### Task 8 — Implement `server.py` scaffolding

- [ ] Create `mcp-server/server.py`:
  ```python
  import os
  from fastmcp import FastMCP
  from fastmcp.exceptions import ToolError
  from client import FarmOSClient

  _REQUIRED_VARS = ["FARMOS_BASE_URL", "FARMOS_USERNAME", "FARMOS_PASSWORD"]
  _missing = [v for v in _REQUIRED_VARS if not os.environ.get(v)]
  if _missing:
      raise EnvironmentError(
          f"Missing required environment variables: {', '.join(_missing)}. "
          "Set them in mcp-server/.env and run with: "
          "uv run --env-file mcp-server/.env agriforestryos-mcp"
      )

  _client = FarmOSClient(
      base_url=os.environ["FARMOS_BASE_URL"],
      username=os.environ["FARMOS_USERNAME"],
      password=os.environ["FARMOS_PASSWORD"],
  )

  mcp = FastMCP("agriforestryos")

  def main() -> None:
      mcp.run()
  ```

- [ ] Verify import (should raise `EnvironmentError` with no env vars — that is correct):
  ```bash
  uv run --project mcp-server python3 -c "import server"
  ```

### Task 9 — Implement `list_asset_types` tool (test-first)

- [ ] Add failing test to `test_tools.py`:
  ```python
  @pytest.mark.asyncio
  async def test_list_asset_types(httpx_mock: HTTPXMock, load_fixture, monkeypatch):
      monkeypatch.setenv("FARMOS_BASE_URL", "http://farmos.local")
      monkeypatch.setenv("FARMOS_USERNAME", "admin")
      monkeypatch.setenv("FARMOS_PASSWORD", "admin")
      fixture = load_fixture("asset_types.json")
      httpx_mock.add_response(
          method="GET",
          url="http://farmos.local/jsonapi/asset_type/asset_type",
          json=fixture,
      )
      from server import list_asset_types
      result = await list_asset_types()
      assert isinstance(result, list)
      assert len(result) >= 2
      assert any(item["id"] == "tree" for item in result)
      assert all("id" in item and "label" in item for item in result)
  ```
  Note: server.py raises EnvironmentError on import without env vars; the monkeypatch handles that. All subsequent tool tests use the same monkeypatch pattern.

- [ ] Run — FAIL (tool not defined).
- [ ] Add to `server.py`:
  ```python
  @mcp.tool()
  async def list_asset_types() -> list[dict]:
      """List all registered asset bundle types in this farmOS instance."""
      data = await _client.get("/jsonapi/asset_type/asset_type")
      return [
          {"id": item["id"], "label": item["attributes"].get("drupal_internal__id", item["id"])}
          for item in data.get("data", [])
      ]
  ```
  Note: inspect `asset_types.json` to find the actual label key (`drupal_internal__id` or `label`). Adjust accordingly.

- [ ] Run — PASS.

### Task 10 — Implement `count_trees` tool with both code paths

- [ ] Add three failing tests (no_filter, fallback, by_species) — see spec design for the URL match patterns and assertion shapes.
- [ ] Implement `count_trees` in `server.py`. Both paths:
  - **Primary** (if probe Task 3 confirmed `meta.count` present): set `params["page[limit]"] = "0"`; read `response["meta"]["count"]`.
  - **Fallback**: count `len(response["data"])`. Document both as supported.
- [ ] Run all 3 count_trees tests — PASS.

### Task 11 — Implement `query_trees` tool (5 test cases)

- [ ] Add failing tests for: default fields, dbh range, sparse field override, limit clamp, species filter.
- [ ] Implement in `server.py`:
  - Default fields: `["id", "name", "species", "dbh_cm", "height_m", "stratum", "health_status", "planting_date"]`.
  - `fields` parameter overrides defaults.
  - `limit` clamped: `effective_limit = min(limit, 500)`.
  - Build params: `fields[asset--tree]` (comma-joined), `page[limit]`, `include=species,stratum,succession_stage,health_status`, plus active filters.
  - For range: use whichever syntax probe Task 3 confirmed.
  - Response flattening: each `item["attributes"]` becomes a flat dict with `id` from `item["id"]`. Resolve relationship labels from `response.get("included", [])` by ID.
- [ ] Run all 5 tests — PASS.

### Task 12 — Implement `get_tree` tool (happy + 404)

- [ ] Add two failing tests.
- [ ] Implement: `GET /jsonapi/asset/tree/{id}?include=species,stratum,succession_stage,health_status` via `client.get_single()`. Flatten `data["attributes"]` + `data["id"]`.
- [ ] Run — PASS.

### Task 13 — Implement `list_infrastructure` tool

- [ ] Add two failing tests (no filter, condition filter).
- [ ] Implement: `GET /jsonapi/asset/infrastructure?include=infrastructure_type`, conditional `filter[condition]=<value>`. Return flat list of `{id, name, infrastructure_type, condition, material, installation_date}`.
- [ ] Run — PASS.
- [ ] Run full suite: `uv run --project mcp-server pytest mcp-server/tests/ -v`. All 13+ tests pass.

### Task 14 — Write `mcp-server/README.md`

- [ ] Sections:
  1. **Prerequisites** — Python 3.11+, `uv`, running farmOS with `jsonapi` + `basic_auth` enabled.
  2. **Install and register with Claude** — `claude mcp add agriforestryos-mcp -- uv run --project /absolute/path/to/mcp-server agriforestryos-mcp` plus the `~/.claude/settings.json` env block.
  3. **Environment variables** — table with the 4 vars (`FARMOS_BASE_URL`, `FARMOS_AUTH_MODE`, `FARMOS_USERNAME`, `FARMOS_PASSWORD`).
  4. **Tool reference** — one subsection per tool with parameters, return shape, example call, "when to use".
  5. **Example session transcript** — plain-text dialogue showing Claude calling all 5 tools against realistic Goldberry data. No synthetic data. No emojis.
  6. **v1.1 preview** — note that `create_tree`, `update_tree`, `archive_tree` are coming once reads are validated.

### Task 15 — Create `.github/workflows/mcp-server-ci.yml`

- [ ] Verify the latest `astral-sh/setup-uv` SHA:
  ```bash
  gh release list --repo astral-sh/setup-uv --limit 3
  gh release view <latest-tag> --repo astral-sh/setup-uv --json tagName,targetCommitish
  ```
- [ ] Create the workflow:
  ```yaml
  name: MCP server CI

  # Runs pytest against the mcp-server/ Python package using pinned uv.
  # Tests are fully mocked (pytest-httpx); no live farmOS required.
  # Advisory only in v1 — promote to required check after first clean run.

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
      name: pytest (Python 3.11, uv)
      runs-on: ubuntu-latest
      timeout-minutes: 5
      steps:
        - uses: actions/checkout@v4

        - uses: astral-sh/setup-uv@<SHA>          # vX.Y.Z — verified at impl time
          with:
            enable-cache: true
            python-version: "3.11"

        - name: Install dependencies
          run: uv sync --project mcp-server

        - name: Run pytest
          run: uv run --project mcp-server pytest mcp-server/tests/ -v
  ```
  Substitute the verified SHA + version comment for `<SHA>` and `vX.Y.Z`.

### Task 16 — Update `ci-farm-syntropic.yml` actionlint file list

- [ ] In `.github/workflows/ci-farm-syntropic.yml`, the `actionlint` job's `run` block has an explicit file list ending with `enforce-changelog.yml`. Add the new workflow:
  ```yaml
              .github/workflows/mcp-server-ci.yml
  ```

### Task 17 — Update `CHANGELOG.md`

- [ ] Under `## [Unreleased]` → `### Added`:
  ```markdown
  - AgriforestryOS fork: `mcp-server/` — read-only FastMCP server exposing AgriforestryOS farm data to Claude via five curated JSON:API tools (`list_asset_types`, `count_trees`, `query_trees`, `get_tree`, `list_infrastructure`). Python 3.11+, uv, FastMCP 3.3.x. No live farmOS required for tests (pytest-httpx fixtures).
  - AgriforestryOS fork: `.github/workflows/mcp-server-ci.yml` — advisory CI workflow running the MCP server pytest suite on every `mcp-server/**` change.
  ```

### Task 18 — Local CI validation before push

- [ ] **actionlint**:
  ```bash
  docker run --rm -v "${PWD}:/repo" -w /repo rhysd/actionlint:1.7.7 -color \
    .github/workflows/mcp-server-ci.yml \
    .github/workflows/ci-farm-syntropic.yml
  ```

- [ ] **gitleaks**:
  ```bash
  docker run --rm -v "${PWD}:/path" zricethezav/gitleaks:v8.18.4 \
    detect --source="/path" --config="/path/.gitleaks.toml" --no-banner --verbose
  ```
  Expected: no leaks. If `admin:admin` in fixtures is flagged, add `mcp-server/tests/fixtures/` to the `.gitleaks.toml` `paths` allowlist.

- [ ] **Full pytest**:
  ```bash
  uv run --project mcp-server pytest mcp-server/tests/ -v
  ```
  Expected: 13+ passed.

- [ ] **`git check-ignore`** verification on `.env`.

- [ ] **Import smoke test** (with dummy env to bypass guard):
  ```bash
  FARMOS_BASE_URL=http://localhost FARMOS_USERNAME=test FARMOS_PASSWORD=test \
    uv run --project mcp-server python3 -c "import server; print('ok')"
  ```

### Task 19 — Open PR and verify CI

- [ ] Branch: `feature/mcp-server-v1`. Stage:
  ```bash
  git checkout -b feature/mcp-server-v1
  git add mcp-server/ .github/workflows/mcp-server-ci.yml .github/workflows/ci-farm-syntropic.yml CHANGELOG.md
  ```
- [ ] Confirm `.env` not staged.
- [ ] Commit: `feat: add mcp-server v1 with 5 read-only JSON:API tools`.
- [ ] Push and open PR against `4.x`.
- [ ] Required checks to wait for:
  - `farm_syntropic CI (fast) / Workflow lint (actionlint)` — must pass (new workflow in scope).
  - `farm_syntropic CI (fast) / Secret scan (gitleaks)` — must pass.
  - `MCP server CI / pytest (Python 3.11, uv)` — advisory; should pass.
  - Smoke test + PHPStan are path-filtered out; will not run.
- [ ] Once green, merge.

---

## Self-Review

**Completeness:** Every file in the spec's "Files Touched" section maps to at least one task. All 13+ tests in the spec test matrix are covered.

**Security:** `mcp-server/.env` is gitignored by root rule (verified in Task 2) and double-guarded by `mcp-server/.gitignore`. No real credentials in fixtures (only `admin:admin` from smoke stack). gitleaks scan in Task 18 catches regressions.

**CI consistency:** New workflow follows the established pattern from `ci-farm-syntropic.yml` / `smoke-farm-syntropic.yml` / `phpstan-farm-syntropic.yml`: `permissions: contents: read`, `concurrency` with `cancel-in-progress: true`, action SHA pinning, `timeout-minutes`. Added to actionlint scope in Task 16.

**`[project.scripts]` entry:** `agriforestryos-mcp = "server:main"` — main is a module-level callable in Task 8, NOT a bound method. Entry points must resolve to plain callables.

**Test isolation:** All tests use `pytest-httpx`'s `HTTPXMock` to intercept at transport layer. No network egress. Safe to run anywhere including CI.

**Deferred scope is honored:** No write operations, no OAuth2, no geospatial tools, no log tools in this plan. README v1.1 preview is the only forward reference.

**Probe-driven decisions documented:** `meta.count` and range-filter syntax are both probed in Task 3, with the result determining which code path is primary. Both code paths exist regardless, so the implementation is robust to either farmOS configuration.
