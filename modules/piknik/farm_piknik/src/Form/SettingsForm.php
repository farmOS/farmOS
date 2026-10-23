<?php

declare(strict_types=1);

namespace Drupal\farm_piknik\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_oauth_client\SecretBox;
use Drupal\farm_piknik\ConnectionFields;
use Drupal\farm_piknik\PlaceDirectory;
use Drupal\farm_piknik\PlaceField;
use Drupal\farm_piknik\SettingsLayout;
use Drupal\farm_piknik\Wordmark;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * OAuth client credentials, connect / disconnect, and selected place.
 */
final class SettingsForm extends FormBase {

  public function __construct(
    private StateInterface $state,
    private OAuthFlow $flow,
    private Connection $connection,
    private SecretBox $secrets,
    private PlaceDirectory $places,
  ) {}

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('state'),
      $container->get('farm_oauth_client.flow'),
      $container->get('farm_oauth_client.connection'),
      $container->get('farm_oauth_client.secrets'),
      $container->get('farm_piknik.places'),
    );
  }

  public function getFormId(): string {
    return 'farm_piknik_settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $connected = $this->flow->connected('piknik');
    $form['#attached']['library'][] = 'farm_piknik/wordmark';
    $form['#attributes']['class'][] = 'farm-piknik-settings';
    $form['wordmark'] = Wordmark::element();
    $form['connection'] = (new SettingsLayout())->connection($connected, $this->connection);
    if ($connected) {
      $form['connection']['participant_id'] = (new PlaceField())->build($this->places, $this->state, $this->messenger());
      $form['connection']['participant_id']['#weight'] = 5;
    }
    $form['actions'] = [
      '#type' => 'actions',
      '#weight' => 100,
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Save'),
        '#name' => 'save',
      ],
    ];
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {
    if (($form_state->getTriggeringElement()['#name'] ?? '') === 'disconnect') {
      return;
    }
    ConnectionFields::validate($form_state, $this->connection, $this->secrets);
  }

  public function submitForm(array &$form, FormStateInterface $form_state): void {
    ConnectionFields::store($form_state, $this->connection);
    $this->storePlace($form_state);
    $this->messenger()->addStatus($this->t('Piknik connection settings saved.'));
  }

  public function signIn(array &$form, FormStateInterface $form_state): void {
    ConnectionFields::store($form_state, $this->connection);
    $url = Url::fromRoute('farm_oauth_client.connect', ['provider' => 'piknik']);
    $url->setOption('query', ['token' => \Drupal::csrfToken()->get($url->getInternalPath())]);
    $form_state->setRedirectUrl($url);
  }

  public function disconnect(array &$form, FormStateInterface $form_state): void {
    $this->flow->disconnect('piknik');
    $this->state->set('farm_piknik.opt_in', FALSE);
    $this->messenger()->addStatus($this->t('This farm is disconnected from Piknik. Harvest sharing is off.'));
  }

  private function storePlace(FormStateInterface $form_state): void {
    if (!$this->flow->connected('piknik')) {
      return;
    }
    $place = (string) $form_state->getValue('participant_id');
    if ($place === '') {
      $this->state->delete('farm_piknik.participant_id');
      return;
    }
    if (preg_match('/^[A-Za-z0-9_-]{8,40}$/', $place)) {
      $this->state->set('farm_piknik.participant_id', $place);
    }
  }

}
