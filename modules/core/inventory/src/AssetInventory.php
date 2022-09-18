<?php

namespace Drupal\farm_inventory;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\fraction\Fraction;

/**
 * Asset inventory logic.
 */
class AssetInventory implements AssetInventoryInterface {

  /**
   * The database object.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected EntityTypeManagerInterface $entityTypeManager;

  /**
   * Class constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TimeInterface $time) {
    $this->database = Database::getConnection();
    $this->entityTypeManager = $entity_type_manager;
    $this->time = $time;
  }

  /**
   * {@inheritdoc}
   */
  public function getInventory(AssetInterface $asset, string $measure = '', int $units = 0, $timestamp = NULL): array {

    // If the asset is new, it won't have inventory.
    if ($asset->isNew()) {
      return [];
    }

    // Get a list of the measure+units pairs we will calculate inventory for.
    $measure_units_pairs = $this->getMeasureUnitsPairs($asset, $measure, $units);

    // Iterate through the measure+units pairs and build inventory summaries.
    $inventories = [];
    foreach ($measure_units_pairs as $pair) {
      $total = $this->calculateInventory($asset, $pair['measure'], $pair['units'], $timestamp);
      $units_label = '';
      if (!empty($pair['units'])) {
        $term = $this->entityTypeManager->getStorage('taxonomy_term')->load($pair['units']);
        if (!empty($term)) {
          $units_label = $term->label();
        }
      }
      $inventories[] = [
        'measure' => $pair['measure'] ? $pair['measure'] : '',
        'value' => $total->toDecimal(0, TRUE),
        'units' => $units_label,
      ];
    }

    // Return the inventory summaries.
    return $inventories;
  }

  /**
   * Query the database for all measure+units inventory pairs of an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset we are querying inventory of.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   *
   * @return array
   *   An array of arrays. Each array will have a 'measure' and 'units' key.
   */
  protected function getMeasureUnitsPairs(AssetInterface $asset, string $measure = '', int $units = 0) {

    // If both a measure and units are provided, that is the only pair.
    if (!empty($measure) && !empty($units)) {
      return [
        [
          'measure' => $measure,
          'units' => $units,
        ],
      ];
    }

    // Query the database for measure+units pairs.
    $query = $this->database->select('quantity', 'q');
    $query->condition('q.inventory_asset', $asset->id());
    $query->addField('q', 'measure');
    $query->addField('q', 'units');
    $query->groupBy('q.measure');
    $query->groupBy('q.units');

    // Filter by measure or units, if provided.
    if (!empty($measure)) {
      $query->condition('q.measure', $measure);
    }
    if (!empty($units)) {
      $query->condition('q.units', $units);
    }

    // Execute the query and build the array of measure+units pairs.
    $result = $query->execute();
    $pairs = [];
    foreach ($result as $row) {
      $pairs[] = [
        'measure' => !empty($row->measure) ? $row->measure : '',
        'units' => !empty($row->units) ? $row->units : 0,
      ];
    }
    return $pairs;
  }

  /**
   * Query the database for the latest asset "reset" adjustment timestamp.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset we are querying inventory of.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return int|null
   *   Returns a unix timestamp, or NULL if no "reset" adjustment is available.
   */
  protected function getLatestResetTimestamp(AssetInterface $asset, string $measure = '', int $units = 0, $timestamp = NULL) {

    // Query the database for the latest asset "reset" adjustment timestamp.
    $query = $this->baseQuery($asset, $measure, $units, $timestamp);
    $query->condition('q.inventory_adjustment', 'reset');
    $query->addExpression('MAX(l.timestamp)');
    return $query->execute()->fetchField();
  }

  /**
   * Calculate the inventory of an asset, for a given measure+units pair.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset we are querying inventory of.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\fraction\Fraction
   *   Returns a Fraction object representing the total inventory.
   */
  protected function calculateInventory(AssetInterface $asset, string $measure = '', int $units = 0, $timestamp = NULL) {

    // Query the database for inventory adjustments of the given asset,
    // measure, and units.
    $adjustments = $this->getAdjustments($asset, $measure, $units, $timestamp);

    // Iterate through the results and calculate the inventory.
    // This will use fraction math to maintain maximum precision.
    $total = new Fraction();
    foreach ($adjustments as $adjustment) {

      // Create a Fraction object from the numerator and denominator.
      $value = new Fraction($adjustment->numerator, $adjustment->denominator);

      // Reset/increment/decrement the total.
      switch ($adjustment->type) {

        // Reset.
        case 'reset':
          $total = $value;
          break;

        // Increment.
        case 'increment':
          $total = $total->add($value);
          break;

        // Decrement.
        case 'decrement':
          $total = $total->subtract($value);
          break;
      }
    }
    return $total;
  }

  /**
   * Query the database for all inventory adjustments of an asset.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset we are querying inventory of.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return array
   *   An array of objects with the following properties: type (reset,
   *   increment, or decrement), numerator, and denominator.
   */
  protected function getAdjustments(AssetInterface $asset, string $measure = '', int $units = 0, $timestamp = NULL) {

    // First, query the database to find the timestamp of the most recent
    // "reset" adjustment log for this asset (if available).
    $latest_reset = $this->getLatestResetTimestamp($asset, $measure, $units, $timestamp);

    // Then, query the database for all inventory adjustments.
    $query = $this->baseQuery($asset, $measure, $units, $timestamp);
    $query->addField('q', 'inventory_adjustment', 'type');
    $query->addField('q', 'value__numerator', 'numerator');
    $query->addField('q', 'value__denominator', 'denominator');
    $query->condition('q.inventory_adjustment', NULL, 'IS NOT NULL');

    // Sort by log timestamp and then ID, ascending.
    $query->orderBy('l.timestamp', 'ASC');
    $query->orderBy('l.id', 'ASC');

    // Filter to logs that happened after the the latest reset, if available.
    if (!empty($latest_reset)) {
      $query->condition('l.timestamp', $latest_reset, '>=');
    }

    // Execute the query and return the results.
    return $query->execute()->fetchAll();
  }

  /**
   * Build a base query for getting asset inventory adjustments.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset we are querying inventory of.
   * @param string $measure
   *   The quantity measure of the inventory. See quantity_measures().
   * @param int $units
   *   The quantity units of the inventory (term ID).
   * @param int|null $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *   If this is NULL (default), the current time will be used.
   *
   * @return \Drupal\Core\Database\Query\SelectInterface
   *   A database query object.
   */
  protected function baseQuery(AssetInterface $asset, string $measure = '', int $units = 0, $timestamp = NULL) {

    // If $timestamp is NULL, use the current time.
    if (is_null($timestamp)) {
      $timestamp = $this->time->getRequestTime();
    }

    // Start with a query of the quantity base table.
    $query = $this->database->select('quantity', 'q');

    // Only include adjustments that reference the asset.
    $query->condition('q.inventory_asset', $asset->id());

    // Filter by measure and units. If either is empty, then explicitly filter
    // to only include rows with NULL values.
    if (!empty($measure)) {
      $query->condition('q.measure', $measure);
    }
    else {
      $query->condition('q.measure', NULL, 'IS NULL');
    }
    if (!empty($units)) {
      $query->condition('q.units', $units);
    }
    else {
      $query->condition('q.units', NULL, 'IS NULL');
    }

    // Join the {log_field_data} table (via reverse reference through
    // the {log__quantity} table).
    $query->join('log__quantity', 'lq', 'q.id = lq.quantity_target_id');
    $query->join('log_field_data', 'l', 'lq.entity_id = l.id');

    // Filter out logs that are not done.
    $query->condition('l.status', 'done');

    // Filter out future logs.
    $query->condition('l.timestamp', $timestamp, '<=');

    // Return the query.
    return $query;
  }

}
