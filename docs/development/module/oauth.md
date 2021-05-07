---
title: OAuth
---

# OAuth

The [Simple OAuth](https://www.drupal.org/project/simple_oauth) module is used
to provide an [OAuth2 standard](https://oauth.net/2/) authorization server.

For documentation on using and authenticating with the farmOS API see [API](/api).

## Providing OAuth Clients

OAuth clients are modeled as "Consumer" entities (provided by the
[Consumers](https://www.drupal.org/project/consumers) module. The `farm_api`
module provides a default client with `client_id = farm`. This can be used for
general usage of the API, but comes with limitations. To create a third party
integration with farmOS a `consumer` entity must be created that identifies
the integration and configures the OAuth Client authorization behavior.

## Scopes

OAuth scopes define different levels of permission. OAuth clients are
configured with the scopes needed for the purposes of a specific integration.
With consumers, these scopes are implemented as Drupal Roles. This means that
OAuth clients interacting with farmOS over the API use the same permission
system as Users normally using the site.

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
- `consumer.redirect_uri` - The URI this client will redirect to when needed.
    - This is used with the Authorization Code authorization flow.
- `consumer.allowed_origins` - Define any allowed origins the farmOS server
  should allow CORS requests from. This is required for any API integrations
  that will run in the browser.
- `consumer.third_party` - Enable if the Consumer represents a third party.
    - Users will skip the "grant" step of the authorization flow for first
      party consumers only.

farmOS extends the `consumers` and `simple_oauth` modules to provide additional
authorization options on consumer entities. These additional options make it
possible to support different third party integration use cases via the same
OAuth Authorization server. They can be configured via the UI or when creating
a consumer entity programmatically.

Authorization options (all are disabled by default):

- `consumer.grant_user_access` - Always grant the authorizing user's access
 to this consumer.
    - This is how the farmOS Field Kit consumer is configured. If this is the
      only option enabled, then the consumer will only be granted the roles
      the user has access to.
- `consumer.limit_requested_access` - Only grant this consumer the scopes
 requested during authorization.
    - By default, all scopes configured with the consumer will be granted
      during authorization. This allows users to select which scopes they want
      to grant the third party during authorization.
- `consumer.limit_user_access` - Never grant the consumer more access than
 the authorizing user.
    - It is possible that clients will be configured with different roles
      than the user that authorizes access to a third party. There are times
      that this may be intentional, but this setting ensures that consumers
      will not be granted more access than the authorizing user.
