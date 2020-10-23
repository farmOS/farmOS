<?php

namespace Drupal\data_stream\Traits;

use Drupal\data_stream\Entity\DataStreamInterface;
use Drupal\fraction\Fraction;
use stdClass;

/**
 * A trait for using the DataStreamSimpleData storage.
 *
 * @see DataStreamStorageInterface
 */
trait DataStreamSqlStorage {

  /**
   * A database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Database table.
   *
   * @var string
   */
  protected $tableName = 'data_stream_data_storage';

  /**
   * {@inheritdoc}
   */
  public function storageGet(DataStreamInterface $stream, array $params) {

    $query = $this->connection->select($this->tableName, 'd');
    $query->fields('d', ['timestamp', 'value_numerator', 'value_denominator']);
    $query->condition('d.id', $stream->id());

    if (isset($params['start']) && is_numeric($params['start'])) {
      $query->condition('d.timestamp', $params['start'], '>=');
    }

    if (isset($params['end']) && is_numeric($params['end'])) {
      $query->condition('d.timestamp', $params['end'], '<=');
    }

    $query->orderBy('d.timestamp', 'DESC');

    $offset = 0;
    if (isset($params['offset']) && is_numeric($params['offset'])) {
      $offset = $params['offset'];
    }

    if (isset($params['limit']) && is_numeric($params['limit'])) {
      $query->range($offset, $params['limit']);
    }

    $result = $query->execute();

    // Build an array of data.
    $data = [];
    foreach ($result as $row) {

      // If name or timestamp are empty, skip.
      if (empty($row->timestamp)) {
        continue;
      }

      // Convert the value numerator and denominator to a decimal.
      $fraction = new Fraction($row->value_numerator, $row->value_denominator);
      $value = $fraction->toDecimal(0, TRUE);

      // Create a data object for the sensor value.
      $point = new stdClass();
      $point->timestamp = $row->timestamp;
      $point->value = $value;
      $data[] = $point;
    }

    // Return the data.
    return $data;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSave(DataStreamInterface $stream, array $data) {

    // If the data is an array of multiple data points, iterate over each and
    // recursively process.
    if (is_array(reset($data))) {
      foreach ($data as $point) {
        $this->storageSave($stream, $point);
      }
      return TRUE;
    }

    // Save a timestamp.
    $timestamp = NULL;

    // If a timestamp is provided, ensure that it is in UNIX timestamp format.
    if (!empty($data['timestamp'])) {

      // If the timestamp is numeric, we're good!
      if (is_numeric($data['timestamp'])) {
        $timestamp = $data['timestamp'];
      }

      // Otherwise, try converting it from a string. If that doesn't work, we
      // throw it out and fall back on REQUEST_TIME set above.
      else {
        $strtotime = strtotime($data['timestamp']);
        if (!empty($strtotime)) {
          $timestamp = $strtotime;
        }
      }
    }

    // Generate a timestamp from the request time. This will only be used if a
    // timestamp is not provided in the JSON data.
    if (empty($timestamp)) {
      $timestamp = \Drupal::time()->getRequestTime();
    }

    // Iterate over the JSON properties.
    foreach ($data as $key => $value) {

      // If the key is "timestamp", skip to the next property in the JSON.
      if ($key == 'timestamp') {
        continue;
      }

      // If the value is not numeric, skip it.
      if (!is_numeric($value)) {
        continue;
      }

      // Create a row to store in the database;.
      $row = [
        'id' => $stream->id(),
        'timestamp' => $timestamp,
      ];

      // Convert the value to a fraction.
      $fraction = Fraction::createFromDecimal($value);
      $row['value_numerator'] = $fraction->getNumerator();
      $row['value_denominator'] = $fraction->getDenominator();

      // Enter the reading into the {data_stream_data_storage} table.
      $this->connection->insert($this->tableName)
        ->fields($row)
        ->execute();
    }

    return TRUE;
  }

}
