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

}
