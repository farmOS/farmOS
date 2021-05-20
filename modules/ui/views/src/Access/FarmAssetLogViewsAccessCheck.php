<?php

namespace Drupal\farm_ui_views\Access;

use Drupal\Core\Database\Database;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Checks access for displaying Views of logs that reference assets.
 */
class FarmAssetLogViewsAccessCheck implements AccessInterface {

  /**
   * The database object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * FarmAssetLogViewsAccessCheck constructor.
   */
  public function __construct() {
    $this->database = Database::getConnection();
  }

  /**
   * A custom access check.
   *
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The route match.
   */
  public function access(RouteMatchInterface $route_match) {

    // If there is no "asset" or "log_type" parameter, bail.
    $asset_id = $route_match->getParameter('asset');
    $log_type = $route_match->getParameter('log_type');
    if (empty($asset_id) || empty($log_type)) {
      return AccessResult::allowed();
    }

    // If the log type is "all", bail.
    if ($log_type == 'all') {
      return AccessResult::allowed();
    }

    // Run a count query to see if there are any logs of this type that
    // reference the asset.
    $query = "SELECT COUNT(*) FROM {log} l LEFT JOIN {log__asset} la ON l.id = la.entity_id WHERE l.type = :log_type AND la.asset_target_id = :asset_id";
    $args = [':log_type' => $log_type, ':asset_id' => $asset_id];
    $log_count = $this->database->query($query, $args)->fetchField();
    return (!empty((int) $log_count)) ? AccessResult::allowed() : AccessResult::forbidden();
  }

}
