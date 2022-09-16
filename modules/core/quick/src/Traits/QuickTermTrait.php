<?php

namespace Drupal\farm_quick\Traits;

use Drupal\taxonomy\Entity\Term;

/**
 * Provides methods for working with terms.
 */
trait QuickTermTrait {

  use QuickStringTrait;

  /**
   * Create a term.
   *
   * @param array $values
   *   An array of values to initialize the term with.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The term entity that was created.
   */
  protected function createTerm(array $values = []) {

    // Trim the term name to 255 characters.
    if (!empty($values['name'])) {
      $values['name'] = $this->trimString($values['name'], 255);
    }

    // Alias 'vocabulary' to 'vid'.
    if (!empty($values['vocabulary'])) {
      $values['vid'] = $values['vocabulary'];
    }

    // Start a new term entity with the provided values.
    /** @var \Drupal\taxonomy\TermInterface $term */
    $term = Term::create($values);

    // Save the term.
    $term->save();

    // Return the term entity.
    return $term;
  }

  /**
   * Given a term name, create or load a matching term entity.
   *
   * @param string $name
   *   The term name.
   * @param string $vocabulary
   *   The vocabulary to search or create in.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The term entity that was created or loaded.
   */
  protected function createOrLoadTerm(string $name, string $vocabulary) {

    // First try to load an existing term.
    $search = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadByProperties(['name' => $name, 'vid' => $vocabulary]);
    if (!empty($search)) {
      return reset($search);
    }

    // Create a new term.
    return $this->createTerm([
      'name' => $name,
      'vid' => $vocabulary,
    ]);
  }

}
