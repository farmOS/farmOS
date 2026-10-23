<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Farm-goods posting settings on the Piknik board (one item each).
 */
final class BoardTriggers {

  use StringTranslationTrait;

  /**
   * @param array<string, string> $crop_options
   *
   * @return array<string, mixed>
   */
  public function elements(StateInterface $state, array $crop_options, ?string $edit_id): array {
    TriggerBootstrap::expandToCrops($state, array_values($crop_options));
    $active = array_values(array_filter(
      TriggerCatalog::all($state),
      static fn(array $t): bool => $t['active'],
    ));
    $edit = $edit_id ? TriggerCatalog::one($state, $edit_id) : NULL;
    return [
      '#type' => 'details',
      '#title' => $this->t('When a harvest is finished'),
      '#open' => TRUE,
      '#attributes' => [
        'class' => ['farm-piknik-board__section', 'farm-piknik-triggers'],
      ],
      'intro' => [
        '#type' => 'html_tag',
        '#tag' => 'p',
        '#value' => $this->t('Choose a farm good and listing description. When you mark that harvest done, a marketplace listing is created automatically.'),
        '#attributes' => ['class' => ['farm-piknik-triggers__intro']],
      ],
      'list' => (new BoardTriggerList())->build($active),
      'editor' => $this->editor($crop_options, $edit),
    ];
  }

  /**
   * @param array<string, string> $crop_options
   * @param array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool}|null $edit
   *
   * @return array<string, mixed>
   */
  private function editor(array $crop_options, ?array $edit): array {
    $crop = $edit['crops'][0] ?? '';
    $description = $edit['template'] ?? '';
    if ($crop !== '' && $description !== '') {
      $description = TriggerBootstrap::resolveTemplate($description, $crop);
    }
    elseif ($crop !== '' && $description === '') {
      $description = 'Fresh ' . $crop . ' for sale';
    }
    return [
      '#type' => 'container',
      // Keep field names as triggers[trig_*] so edit rebuild can clear them reliably.
      '#tree' => TRUE,
      '#parents' => ['triggers'],
      '#attributes' => ['class' => ['farm-piknik-trigger', 'farm-piknik-triggers__editor']],
      'title' => [
        '#type' => 'html_tag',
        '#tag' => 'h3',
        '#value' => $edit ? $this->t('Edit harvest listing') : $this->t('New harvest listing'),
        '#attributes' => ['class' => ['farm-piknik-trigger__title']],
      ],
      'edit_id' => [
        '#type' => 'value',
        '#value' => $edit['id'] ?? '',
      ],
      'trig_crop' => [
        '#type' => 'select',
        '#title' => $this->t('Farm good'),
        '#options' => $crop_options,
        '#empty_option' => $this->t('- Select -'),
        '#default_value' => $crop,
        '#required' => TRUE,
      ],
      'trig_type' => [
        '#type' => 'select',
        '#title' => $this->t('Listing type'),
        '#options' => TriggerCatalog::TYPES,
        '#default_value' => $edit['listing_type'] ?? HarvestPayload::SELL,
      ],
      'trig_mode' => [
        '#type' => 'value',
        '#value' => 'auto',
      ],
      'trig_template' => [
        '#type' => 'textfield',
        '#title' => $this->t('Listing description'),
        '#default_value' => $description,
        '#maxlength' => 200,
        '#description' => $this->t('Include the farm good name in the description.'),
      ],
      'actions' => [
        '#type' => 'container',
        '#attributes' => ['class' => ['farm-piknik-trigger__actions']],
        'save' => [
          '#type' => 'submit',
          '#value' => 'save',
          '#name' => 'save_trigger',
          '#submit' => ['::submitTriggerAction'],
          '#button_type' => 'primary',
          '#attributes' => ['class' => ['farm-piknik-trigger__save']],
        ],
        'cancel' => $edit ? [
          '#type' => 'submit',
          '#value' => $this->t('Cancel'),
          '#name' => 'cancel_edit',
          '#submit' => ['::submitTriggerAction'],
          '#limit_validation_errors' => [],
        ] : [],
      ],
    ];
  }

  /**
   * @return array{id: string, crops: list<string>, listing_type: string, template: string, mode: string, active: bool}
   */
  public function fromEditor(FormStateInterface $form_state): array {
    $crop = trim((string) $form_state->getValue(['triggers', 'trig_crop']));
    $description = trim((string) $form_state->getValue(['triggers', 'trig_template']));
    if ($description === '') {
      $description = 'Fresh ' . $crop . ' for sale';
    }
    else {
      $description = TriggerBootstrap::resolveTemplate($description, $crop);
    }
    $id = (string) $form_state->getValue(['triggers', 'edit_id']);
    return TriggerCatalog::normalize([
      'id' => $id !== '' ? $id : TriggerCatalog::newId(),
      'crops' => [$crop],
      'listing_type' => (string) $form_state->getValue(['triggers', 'trig_type']),
      'template' => $description,
      'mode' => 'auto',
      'active' => TRUE,
    ]);
  }

}
