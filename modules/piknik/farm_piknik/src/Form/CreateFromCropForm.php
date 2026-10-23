<?php

declare(strict_types=1);

namespace Drupal\farm_piknik\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\farm_piknik\FinishedHarvests;
use Drupal\farm_piknik\HarvestPayload;
use Drupal\farm_piknik\ListingClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a marketplace listing from a completed harvest log.
 */
final class CreateFromCropForm extends FormBase {

  protected ListingClient $client;

  protected StateInterface $appState;

  protected FinishedHarvests $harvests;

  public function __construct(
    ListingClient $client,
    StateInterface $app_state,
    FinishedHarvests $harvests,
  ) {
    $this->client = $client;
    $this->appState = $app_state;
    $this->harvests = $harvests;
  }

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('farm_piknik.listing_client'),
      $container->get('state'),
      $container->get('farm_piknik.finished_harvests'),
    );
  }

  public function getFormId(): string {
    return 'farm_piknik_create_from_crop';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $options = $this->harvests->options();
    $form['help'] = [
      '#type' => 'html_tag',
      '#tag' => 'p',
      '#value' => $options === []
        ? $this->t('No completed harvests found. Mark a harvest as done in Records, then return here.')
        : $this->t('Select a completed harvest. The farm good and quantity are used when available.'),
    ];
    if ($options === []) {
      $form['actions'] = [
        '#type' => 'actions',
        'back' => [
          '#type' => 'link',
          '#title' => $this->t('Back'),
          '#url' => \Drupal\Core\Url::fromRoute('farm_piknik.board'),
          '#attributes' => ['class' => ['button']],
        ],
      ];
      return $form;
    }
    $form['harvest_id'] = [
      '#type' => 'select',
      '#title' => $this->t('Harvest'),
      '#options' => $options,
      '#empty_option' => $this->t('- Select -'),
      '#required' => TRUE,
    ];
    $form['farm_listing_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Listing type'),
      '#options' => [
        HarvestPayload::SELL => $this->t('For sale'),
        HarvestPayload::WASTE => $this->t('Surplus'),
        'wanted_to_buy' => $this->t('Looking to buy'),
      ],
      '#default_value' => HarvestPayload::SELL,
      '#required' => TRUE,
    ];
    $form['farm_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Listing description'),
      '#maxlength' => 200,
      '#description' => $this->t('Optional. Defaults to the farm good name.'),
    ];
    $form['farm_quantity'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quantity'),
      '#maxlength' => 40,
      '#description' => $this->t('Optional. Defaults to the harvest quantity when recorded.'),
    ];
    $form['actions'] = [
      '#type' => 'actions',
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Post to the marketplace'),
        '#button_type' => 'primary',
      ],
    ];
    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    $details = $this->harvests->details((string) $form_state->getValue('harvest_id'));
    if ($details === NULL) {
      $this->messenger()->addError($this->t('That harvest is not available. Select another.'));
      return;
    }
    $place = (string) $this->appState->get('farm_piknik.participant_id', '');
    $desc = trim((string) $form_state->getValue('farm_description'));
    $qty = trim((string) $form_state->getValue('farm_quantity'));
    if ($qty === '') {
      $qty = $details['quantity'];
    }
    $payload = HarvestPayload::adHoc([
      'participantId' => $place,
      'listingType' => (string) $form_state->getValue('farm_listing_type'),
      'title' => $details['crop'],
      'description' => $desc !== '' ? $desc : $details['crop'],
      'quantity' => $qty,
    ]);
    if ($payload === NULL) {
      $this->messenger()->addError($this->t('Choose a place in setup first, then try again.'));
      return;
    }
    try {
      $this->client->create($payload);
      $this->messenger()->addStatus($this->t('Listed @item on the marketplace.', ['@item' => $details['crop']]));
    }
    catch (\Throwable $e) {
      $this->messenger()->addError($e->getMessage());
      return;
    }
    $form_state->setRedirect('farm_piknik.board');
  }

}
