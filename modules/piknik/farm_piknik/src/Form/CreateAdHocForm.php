<?php

declare(strict_types=1);

namespace Drupal\farm_piknik\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\farm_piknik\HarvestPayload;
use Drupal\farm_piknik\ListingClient;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Create a marketplace listing without a harvest record.
 */
final class CreateAdHocForm extends FormBase {

  protected ListingClient $client;

  protected StateInterface $appState;

  public function __construct(ListingClient $client, StateInterface $app_state) {
    $this->client = $client;
    $this->appState = $app_state;
  }

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('farm_piknik.listing_client'),
      $container->get('state'),
    );
  }

  public function getFormId(): string {
    return 'farm_piknik_create_adhoc';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['adhoc_title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 80,
      '#required' => TRUE,
    ];
    $form['adhoc_type'] = [
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
    $form['adhoc_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#maxlength' => 200,
      '#description' => $this->t('Optional.'),
    ];
    $form['adhoc_quantity'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Quantity'),
      '#maxlength' => 40,
      '#description' => $this->t('Optional.'),
    ];
    $form['adhoc_wanted'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Wanted in return'),
      '#maxlength' => 120,
      '#description' => $this->t('Optional. Used for looking-to-buy listings.'),
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
    $place = (string) $this->appState->get('farm_piknik.participant_id', '');
    $payload = HarvestPayload::adHoc([
      'participantId' => $place,
      'listingType' => (string) $form_state->getValue('adhoc_type'),
      'title' => (string) $form_state->getValue('adhoc_title'),
      'description' => (string) $form_state->getValue('adhoc_description'),
      'quantity' => (string) $form_state->getValue('adhoc_quantity'),
      'wantedInReturn' => (string) $form_state->getValue('adhoc_wanted'),
    ]);
    if ($payload === NULL) {
      $this->messenger()->addError($this->t('Enter a title and choose a place in setup first.'));
      return;
    }
    try {
      $this->client->create($payload);
      $this->messenger()->addStatus($this->t('Listing posted to the marketplace.'));
    }
    catch (\Throwable $e) {
      $this->messenger()->addError($e->getMessage());
      return;
    }
    $form_state->setRedirect('farm_piknik.board');
  }

}
