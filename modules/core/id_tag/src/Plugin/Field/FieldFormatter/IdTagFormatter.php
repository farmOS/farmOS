<?php

namespace Drupal\farm_id_tag\Plugin\Field\FieldFormatter;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'id tag' formatter.
 *
 * @FieldFormatter(
 *   id = "id_tag",
 *   label = @Translation("ID tag"),
 *   field_types = {
 *     "id_tag"
 *   }
 * )
 */
class IdTagFormatter extends FormatterBase {

  /**
   * The tag_type entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $tagTypeStorage;

  /**
   * Constructs an IdTagFormatter object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The formatter settings.
   * @param string $label
   *   The formatter label display setting.
   * @param string $view_mode
   *   The view mode.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, $label, $view_mode, array $third_party_settings, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $label, $view_mode, $third_party_settings);
    $this->tagTypeStorage = $entity_type_manager->getStorage('tag_type');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['label'],
      $configuration['view_mode'],
      $configuration['third_party_settings'],
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {

      // Render the ID if it exists.
      if (!empty($item->id)) {
        $elements[$delta]['id'] = [
          '#markup' => $this->t('ID: @value', ['@value' => $item->id]),
        ];
      }

      // Render the type if it exists. Use the tag_type label.
      if (!empty($item->type) && $tag_type = $this->tagTypeStorage->load($item->type)) {
        $elements[$delta]['type'] = [
          '#markup' => $this->t('Type: @value', ['@value' => $tag_type->label()]),
        ];
      }

      // Render the location if it exists.
      if (!empty($item->location)) {
        $elements[$delta]['location'] = [
          '#markup' => $this->t('Location: @value', ['@value' => $item->location]),
        ];
      }
    }

    $elements['#attached']['library'][] = 'farm_id_tag/id_tag_field';
    return $elements;
  }

}
