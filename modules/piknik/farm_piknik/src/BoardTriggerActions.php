<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\State\StateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Handles board form submits for farm-goods posting settings.
 */
final class BoardTriggerActions {

  use MessengerTrait;
  use StringTranslationTrait;

  public function handle(StateInterface $state, FormStateInterface $form_state, ListingClient $client): void {
    $name = (string) ($form_state->getTriggeringElement()['#name'] ?? '');
    if ($name === 'save_trigger') {
      $this->save($state, $form_state);
      return;
    }
    if ($name === 'cancel_edit') {
      $form_state->set('edit_trigger_id', NULL);
      $this->clearEditorInput($form_state);
      return;
    }
    if (str_starts_with($name, 'edit:')) {
      $form_state->set('edit_trigger_id', substr($name, 5));
      // Rebuild ignores #default_value when empty submitted fields remain.
      $this->clearEditorInput($form_state);
      return;
    }
    if (str_starts_with($name, 'remove:')) {
      $this->remove($state, substr($name, 7));
      return;
    }
    if (str_starts_with($name, 'run:')) {
      $this->run($state, $client, substr($name, 4), $form_state);
    }
  }

  private function clearEditorInput(FormStateInterface $form_state): void {
    $keys = ['edit_id', 'trig_crop', 'trig_type', 'trig_mode', 'trig_template', 'trig_active'];
    $input = $form_state->getUserInput();
    foreach ($keys as $key) {
      unset($input[$key], $input['triggers'][$key], $input['triggers']['editor'][$key]);
      $form_state->unsetValue([$key]);
      $form_state->unsetValue(['triggers', $key]);
      $form_state->unsetValue(['triggers', 'editor', $key]);
    }
    $form_state->setUserInput($input);
  }

  private function save(StateInterface $state, FormStateInterface $form_state): void {
    $trigger = (new BoardTriggers())->fromEditor($form_state);
    if ($trigger['crops'] === [] || $trigger['crops'][0] === '') {
      $this->messenger()->addError($this->t('Select a farm good.'));
      return;
    }
    $list = TriggerCatalog::all($state);
    $found = FALSE;
    foreach ($list as &$row) {
      if ($row['id'] === $trigger['id']) {
        $row = $trigger;
        $found = TRUE;
        break;
      }
    }
    unset($row);
    if (!$found) {
      $list[] = $trigger;
    }
    TriggerCatalog::save($state, $list);
    $form_state->set('edit_trigger_id', NULL);
    $this->clearEditorInput($form_state);
    $this->messenger()->addStatus($this->t('Saved harvest listing for @item.', [
      '@item' => $trigger['crops'][0],
    ]));
  }

  private function remove(StateInterface $state, string $id): void {
    $this->deleteTrigger($state, $id);
    $this->messenger()->addStatus($this->t('Harvest listing rule deleted.'));
  }

  /**
   * Drops a trigger from state (no messenger side effects).
   */
  private function deleteTrigger(StateInterface $state, string $id): void {
    $list = array_values(array_filter(
      TriggerCatalog::all($state),
      static fn(array $t): bool => $t['id'] !== $id,
    ));
    TriggerCatalog::save($state, $list);
  }

  private function run(StateInterface $state, ListingClient $client, string $id, FormStateInterface $form_state): void {
    $trigger = TriggerCatalog::one($state, $id);
    $place = (string) $state->get('farm_piknik.participant_id', '');
    $crop = $trigger['crops'][0] ?? trim((string) $form_state->getValue('run_crop'));
    if ($trigger === NULL || $crop === '' || $place === '') {
      $this->messenger()->addError($this->t('Select a farm good before listing.'));
      return;
    }
    $payload = HarvestPayload::fromTrigger($crop, $place, $trigger);
    if ($payload === NULL) {
      $this->messenger()->addError($this->t('Could not create that listing.'));
      return;
    }
    try {
      $client->create($payload);
      // Immediate (manual) is one-shot: drop the card once the listing exists.
      if (($trigger['mode'] ?? '') === 'manual') {
        $this->deleteTrigger($state, $id);
      }
      $this->messenger()->addStatus($this->t('Listed @item on the marketplace.', ['@item' => $crop]));
    }
    catch (\Throwable $e) {
      $this->messenger()->addError($e->getMessage());
    }
  }

}
