<?php

namespace Drupal\data_stream_notification\Plugin\DataStream\NotificationCondition;

use Drupal\Core\Form\FormStateInterface;

/**
 * Numeric notification condition.
 *
 * @NotificationCondition(
 *   id = "numeric",
 *   label = @Translation("Numeric"),
 *   context_definitions = {
 *     "value" = @ContextDefinition("float", label = @Translation("value"))
 *   }
 * )
 */
class NumericCondition extends NotificationConditionBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
      'negate' => FALSE,
      'condition' => '<',
      'threshold' => 0,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form['condition'] = [
      '#type' => 'select',
      '#title' => $this->t('Condition'),
      '#options' => [
        '<' => '<',
        '>' => '>',
      ],
      '#default_value' => $this->configuration['condition'] ?? '<',
    ];

    $form['threshold'] = [
      '#type' => 'number',
      '#step' => 'any',
      '#title' => $this->t('Threshold'),
      '#default_value' => $this->configuration['threshold'] ?? 0,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function evaluate(): bool {

    // Bail if an invalid context.
    if ($this->validateContexts()->count()) {
      return FALSE;
    }

    // Load condition and threshold from configuration.
    $condition = $this->configuration['condition'];
    $threshold = $this->configuration['threshold'];

    $result = FALSE;
    $value = $this->getContextValue('value');
    switch ($condition) {
      case '<':
        $result = $value < $threshold;
        break;

      case '>':
        $result = $value > $threshold;
        break;
    }
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  public function summary() {

    // Bail if no configuration is provided.
    if (!isset($this->configuration['condition']) || !isset($this->configuration['threshold'])) {
      return '';
    }

    // Get configuration.
    $condition = $this->configuration['condition'];
    $threshold = $this->configuration['threshold'];

    // Handle negation.
    if (!empty($this->configuration['negate'])) {
      if ($condition === '<') {
        return $this->t('The value is not less than @threshold.', ['@threshold' => $threshold]);
      }
      else {
        return $this->t('The value is not greater than @threshold.', ['@threshold' => $threshold]);
      }
    }

    // Standard.
    else {
      if ($condition === '<') {
        return $this->t('The value is less than @threshold.', ['@threshold' => $threshold]);
      }
      else {
        return $this->t('The value is greater than @threshold.', ['@threshold' => $threshold]);
      }
    }
  }

}
