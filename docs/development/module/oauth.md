# OAuth

The [Simple OAuth](https://www.drupal.org/project/simple_oauth) module is used
to provide an [OAuth2 standard](https://oauth.net/2/) authorization server.

For documentation on using and authenticating with the farmOS API see [API](/api).

## Providing OAuth Scopes

OAuth Scopes define different levels of access. The farmOS server
implements scopes that represent individual roles or permissions. Users will
authorize clients with one or more scopes that determine how much access they
have to data on the server.

OAuth scopes are provided to the server using scope provider plugins. Each
scope provider determines how scopes are implemented and created. The OAuth
server must choose a single scope provider to provide all the scopes
necessary for the server's authorization needs. All scopes use the same
configuration and provide the same features regardless of the scope provider.

The [Simple OAuth](https://www.drupal.org/project/simple_oauth) module
provides two scope providers: `static` and `dynamic`. The `static` scope
provider implements scopes as a `yaml` plugin that must be provided by modules
and prevents scopes from being modified. The `dynamic` scope provider
implements scopes as a config entity and allows scopes to be created and
modified via the UI. Modules can provide `dynamic` scopes as well but there are
no guarantees that these scopes will remain unmodified.

farmOS defaults to using the `static` scope provider. This allows modules
providing OAuth scopes to guarantee that their scopes exist unmodified within
the server. The farmOS administrator can change to using the `dynamic` scope
provider if necessary, but may need to re-create any `static` scopes that
are needed for integrations provided by other modules.

The farmOS Default Roles module provides a `static` OAuth scope for each of the
default roles: `farm_manager`, `farm_worker`, and `farm_viewer`.

### Scope Configuration

All scopes use the same configuration and provide the same features
regardless of the scope provider:
- Scopes must provide a `name` to uniquely identify the scope
- Scopes must provide a `description`
- Scopes must specify if they are an `umbrella` scope. Umbrella scopes are
  only used as parent for child scopes to reference and do not specify a
  `granularity`.
- Scopes must configure which `grant_types` they allow. Each grant type can
  include an optional `description` to describe how the scope is used in the
  context of each grant type.
- Scopes can optionally specify a `parent` scope that the scope is a part of.
  When the parent scope is requested, all of its child scopes are granted as
  well.
- Scopes must specify a `granularity` if they are not an `umbrella` scope.
  This value must be equal to `permission` or `role`. The scope must
  provide a single value for the `permission` or `role` it is associated with.

This configuration is most easily demonstrated with
`static` scopes that are provided in a `module.oauth2_scopes.yml` plugin file.

```yaml
"scope:name":
  description: string (required)
  umbrella: boolean (required)
  grant_types: (required)
    GRANT_TYPE_PLUGIN_ID: (required: only known grant types)
      status: boolean (required)
      description: string
  parent: string
  granularity: string (required: if umbrella is FALSE, values: permission or role)
  permission: string (required: if umbrella is FALSE and granularity set to permission)
  role: string (required: if umbrella is FALSE and granularity set to role)
```

An example of the static `farm_manager` scope provided by the farmOS Role
Roles mdoule:
```yaml
farm_manager:
  description: 'Grants access to the Farm Manager role.'
  umbrella: false
  grant_types:
    authorization_code:
      status: true
    refresh_token:
      status: true
    password:
      status: true
  granularity: 'role'
  role: 'farm_manager'
```

## Providing OAuth Clients

OAuth clients are modeled as "Consumer" entities (provided by the
[Consumers](https://www.drupal.org/project/consumers) module. To create
integrations with farmOS a `consumer` entity must be created that
identifies the integration and configures the OAuth Client for the desired
authorization behavior.

The core `farm_api_default_consumer` module provides a default client with
`client_id = farm` that can use the `password` and `refresh_token` grant. You
can use this client for general usage of the API, like writing a script that
communicates with *your* farmOS server, but it comes with limitations.

## Client Configuration

Standard Consumer configuration:

- `consumer.label` - A label used to identify the third party integration.
- `consumer.client_id` - An optional `client_id` machine name to identify the
  consumer. The `simple_oauth` module uses a UUID by default, but a machine
  name makes it easier to identify clients across multiple farmOS servers.
- `consumer.secret` - A `client_secret` used to secure the OAuth client.
- `consumer.confidential` - A boolean indicating whether the client secret
  needs to be validated.
    - Most farmOS third party integrations will disable this. Otherwise the
      same `client_secret` must be configured on all farmOS servers, or the
      third party must keep track of a different secret for each server. This
      challenge is due to the nature of farmOS being a self-hosted application.
- `consumer.user_id` - When no specific user is authenticated Drupal will use
  this user as the author of all the actions made by this consumer.
    - This is only the case during the `Client Credentials` authorization flow.
- `consumer.grant_types` - A list of the grant types that the client allows.
- `consumer.scopes` - A list of default scopes that will be granted for this
  client if no scopes are requested during the authorization flow. No scopes
  will be granted that the user does not have access to.
- `consumer.access_token_expiration` - The lifetime of access tokens in seconds.
- `consumer.refresh_token_expiration` - The lifetime of refresh tokens in
  seconds.
- `consumer.redirect_uri` - The URI this client will redirect to when needed.
    - This is used with the Authorization Code authorization flow.
- `consumer.allowed_origins` - Define any allowed origins the farmOS server
  should allow CORS requests from. This is required for any API integrations
  that will run in the browser.
- `consumer.third_party` - Enable if the Consumer represents a third party.
    - Users will skip the "grant" step of the authorization flow for first
      party consumers only.
