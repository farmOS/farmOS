<?php

namespace Drupal\farm_api\Repositories;

use Drupal\simple_oauth\Repositories\ScopeRepository;
use Drupal\user\RoleInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * Decorates the simple_oauth ScopeRepository.
 *
 * Alter the default behavior to account for additional consumer config options:
 *   - consumer.grant_user_access: Always grant the user's roles.
 *   - consumer.limit_requested_access: Always limit to the requested scopes.
 *   - consumer.limit_user_access: Always limit access to what the user has.
 *
 * @ingroup farm
 */
class FarmScopeRepository extends ScopeRepository {

  /**
   * {@inheritdoc}
   */
  public function finalizeScopes(array $scopes, $grant_type, ClientEntityInterface $client_entity, $user_identifier = NULL) {

    // Start a list of allowed roles.
    $allowed_roles = [];

    // Load the consumer entity.
    /** @var \Drupal\consumers\Entity\Consumer $client_drupal_entity */
    $consumer_entity = $client_entity->getDrupalEntity();

    // Load role ids of roles the consumer has.
    $consumer_roles = array_map(function ($role) {
      return $role['target_id'];
    }, $consumer_entity->get('roles')->getValue());

    // Include consumer roles.
    // By default all consumer roles are available to authorization.
    $allowed_roles = array_merge($allowed_roles, $consumer_roles);

    // Load the default user associated with the consumer.
    // This is an optional setting, so it may not exist.
    $default_user = NULL;
    try {
      $default_user = $client_entity->getDrupalEntity()->get('user_id')->entity;
    }
    catch (\InvalidArgumentException $e) {
      // Do nothing.
    }

    // Load the user associated with the token.
    // If there is no user, use the default user.
    /** @var \Drupal\user\UserInterface $user */
    $user = $user_identifier
      ? $this->entityTypeManager->getStorage('user')->load($user_identifier)
      : $default_user;
    if (!$user) {
      return [];
    }

    // Load the user's roles.
    $user_roles = $user->getRoles();

    // Include the user's roles if enabled.
    if ($consumer_entity->get('grant_user_access')->value) {
      $allowed_roles = array_merge($allowed_roles, $user_roles);
    }

    /* Limit the roles granted to the token. */

    // Limit to requested roles if enabled.
    if ($consumer_entity->get('limit_requested_access')->value) {

      // Save the requested scopes (roles) that were passed to this
      // finalizeScopes() method.
      $requested_roles = array_map(function (ScopeEntityInterface $scope) {
        return $scope->getIdentifier();
      }, $scopes);

      // Reduce the requested roles to only those in allowed roles.
      // This prevents additional roles being granted than the user
      // and consumer have available.
      $allowed_requested_roles = array_filter($requested_roles, function ($role_id) use ($allowed_roles) {
        return in_array($role_id, $allowed_roles);
      });

      // Filter the allowed roles to only those requested.
      $allowed_roles = array_intersect($allowed_roles, $allowed_requested_roles);
    }

    // Limit to roles the user already has, if enabled.
    if ($consumer_entity->get('limit_user_access')->value) {
      $allowed_roles = array_intersect($allowed_roles, $user_roles);
    }

    // Always include the authenticated role.
    $allowed_roles[] = RoleInterface::AUTHENTICATED_ID;

    // Build a new list of ScopeEntityInterface to return.
    $scopes = [];
    foreach ($allowed_roles as $role_id) {
      $scopes = $this->addRoleToScopes($scopes, $role_id);
    }
    return $scopes;
  }

}
