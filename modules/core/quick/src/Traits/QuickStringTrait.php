<?php

namespace Drupal\farm_quick\Traits;

/**
 * Provides methods for generating record name strings.
 */
trait QuickStringTrait {

  /**
   * Trims a string down to the specified length, respecting word boundaries.
   *
   * @param string $value
   *   The string which should be trimmed.
   * @param int $max_length
   *   Maximum length of the string, the rest gets truncated.
   * @param string $suffix
   *   A suffix to append to the end of the string, if it is trimmed.
   *   Defaults to an ellipsis (…).
   *
   * @return string
   *   The trimmed string.
   */
  protected function trimString(string $value, int $max_length, string $suffix = '…') {

    // First trim whitespace.
    $value = trim($value);

    // If the string fits, we're done here.
    if (mb_strlen($value) <= $max_length) {
      return $value;
    }

    // Use PHP wordwrap() to wrap the text to multiple lines on word boundaries,
    // then explode() the lines into an array so we can take the first line.
    // Subtract the suffix length so we can add it afterwards.
    $width = $max_length - mb_strlen($suffix);
    if (empty($width)) {
      return $suffix;
    }
    $lines = explode("\n", wordwrap($value, $width, "\n", TRUE));
    return reset($lines) . $suffix;
  }

  /**
   * Concatenate prioritized strings together into one, respecting max length.
   *
   * @param array $strings
   *   An array of string values to include, in order of appearance. These
   *   strings will be concatenated with a space. This array can be optionally
   *   keyed to allow the $priority_keys argument to specify which strings
   *   should not be truncated (if possible).
   * @param array $priority_keys
   *   An array of strings that correspond to keys in the $strings array which
   *   should be given higher priority. This is used if the total length of the
   *   generated string exceeds the maximum allowed length.
   * @param int $max_length
   *   The maximum length of the final string. Defaults to 255.
   * @param string $suffix
   *   A suffix to append to the end of the string, if it is trimmed.
   *   Defaults to an ellipsis (…).
   *
   * @return string
   *   The joined, prioritized, and trimmed string.
   */
  protected function prioritizedString(array $strings = [], array $priority_keys = [], int $max_length = 255, string $suffix = '…') {

    // Trim each string and remove empty ones.
    foreach ($strings as $key => $string) {
      $strings[$key] = trim($string);
      if (empty($strings[$key])) {
        unset($strings[$key]);
      }
    }

    // Concatenate all the strings together, separated by spaces.
    $combined = implode(' ', $strings);

    // If the full string fits, return it.
    if (mb_strlen($combined) <= $max_length) {
      return $combined;
    }

    // If no priority keys were specified, or all keys are priority, trim the
    // combined string and return it.
    if (empty($priority_keys) || count($strings) == count($priority_keys)) {
      return $this->trimString($combined, $max_length, $suffix);
    }

    // Split strings into priority and non-priority.
    $priority_strings = [];
    $non_priority_strings = [];
    foreach ($strings as $key => $value) {
      if (in_array($key, $priority_keys)) {
        $priority_strings[$key] = $value;
      }
      else {
        $non_priority_strings[$key] = $value;
      }
    }

    // If the priority strings alone will not fit, join and trim them alone.
    $priority_string = implode(' ', $priority_strings);
    if (mb_strlen($priority_string) > $max_length) {
      return $this->trimString($priority_string, $max_length);
    }

    // Measure how many characters are left after accounting for priority
    // strings and spaces between strings.
    $remaining_length = $max_length - mb_strlen($priority_string) - count($non_priority_strings);

    // Divide the remaining characters by the number of non-priority strings.
    $non_priority_max_length = floor($remaining_length / count($non_priority_strings));

    // If the maximum length of non-priority strings is greater than zero,
    // trim each, concatenate the full string, perform a final trim, and return.
    if (!empty($non_priority_max_length)) {
      $parts = [];
      foreach ($strings as $key => $value) {
        if (in_array($key, $priority_keys)) {
          $parts[] = $value;
        }
        else {
          $parts[] = $this->trimString($value, $non_priority_max_length);
        }
      }
      return $this->trimString(implode(' ', $parts), $max_length);
    }

    // Otherwise, trim and return the priority string.
    return $this->trimString($priority_string, $max_length);
  }

  /**
   * Generate a summary of entity labels.
   *
   * Note that this does NOT sanitize the entity labels. It is the
   * responsibility of downstream code to do so, if it is printing text to the
   * page.
   *
   * @param array $entities
   *   An array of entities.
   * @param int $cutoff
   *   The number of entity labels to include before summarizing the rest.
   *   If the number of entities exceeds the cutoff, the rest will be summarized
   *   as "(+X more)". If the number of entities is less than or equal to the
   *   cutoff, or if the cutoff is 0, all entity labels will be included.
   *
   * @return string
   *   Returns a string summarizing the entity labels.
   */
  protected function entityLabelsSummary(array $entities, $cutoff = 3) {
    $names = [];
    foreach ($entities as $entity) {
      $names[] = $entity->label();
    }
    if ($cutoff != 0) {
      array_splice($names, $cutoff);
    }
    $output = implode(', ', $names);
    $diff = count($entities) - count($names);
    if ($diff > 0) {
      $output .= ' (+' . $diff . ' ' . t('more') . ')';
    }
    return $output;
  }

}
