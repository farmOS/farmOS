<?php

declare(strict_types=1);

namespace Drupal\farm_piknik\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Drupal\Core\Url;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_piknik\BoardCreateLinks;
use Drupal\farm_piknik\BoardMarketplace;
use Drupal\farm_piknik\BoardTriggerActions;
use Drupal\farm_piknik\BoardTriggers;
use Drupal\farm_piknik\BrowseRadius;
use Drupal\farm_piknik\FarmGoods;
use Drupal\farm_piknik\ListingClient;
use Drupal\farm_piknik\ListingDirectory;
use Drupal\farm_piknik\Wordmark;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Day-to-day Piknik board: listings, matches, create, harvest triggers.
 *
 * Services use protected (not private promoted) properties so Drupal form
 * serialization can restore them on submit (DependencySerializationTrait).
 */
final class BoardForm extends FormBase {

  protected OAuthFlow $flow;

  protected StateInterface $appState;

  protected ListingDirectory $listings;

  protected ListingClient $client;

  protected FarmGoods $farm_goods;

  public function __construct(
    OAuthFlow $flow,
    StateInterface $app_state,
    ListingDirectory $listings,
    ListingClient $client,
    FarmGoods $farm_goods,
  ) {
    $this->flow = $flow;
    $this->appState = $app_state;
    $this->listings = $listings;
    $this->client = $client;
    $this->farm_goods = $farm_goods;
  }

  public static function create(ContainerInterface $container): static {
    return new static(
      $container->get('farm_oauth_client.flow'),
      $container->get('state'),
      $container->get('farm_piknik.listings'),
      $container->get('farm_piknik.listing_client'),
      $container->get('farm_piknik.farm_goods'),
    );
  }

  public function getFormId(): string {
    return 'farm_piknik_board';
  }

  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form['#attached']['library'][] = 'farm_piknik/wordmark';
    $form['#attributes']['class'][] = 'farm-piknik-board';
    $radius = BrowseRadius::get($this->appState);
    $form['header'] = Wordmark::board($radius);
    if (!$this->flow->connected('piknik')) {
      $url = Url::fromRoute('farm_piknik.settings')->toString();
      $form['prompt'] = [
        '#markup' => '<p class="farm-piknik-board__prompt">' . $this->t('Connect this farm first. <a href=":url">Open setup</a>.', [':url' => $url]) . '</p>',
      ];
      return $form;
    }
    $goods_options = $this->farm_goods->options();
    $place = (string) $this->appState->get('farm_piknik.participant_id', '');
    $form['create'] = (new BoardCreateLinks())->build();
    try {
      $mine = $this->listings->mine($place);
      $matches = $place !== '' ? $this->listings->matching($this->farm_goods->names(), $place, $radius) : [];
      $nearby = $place !== '' ? $this->listings->nearbyOthers($place, 25, $radius) : [];
      $form['marketplace'] = (new BoardMarketplace())->build($mine, $matches, $nearby, $radius);
    }
    catch (\Throwable $e) {
      $this->messenger()->addError($e->getMessage());
    }
    $edit_id = $form_state->get('edit_trigger_id');
    $form['triggers'] = (new BoardTriggers())->elements(
      $this->appState,
      $goods_options,
      is_string($edit_id) && $edit_id !== '' ? $edit_id : NULL,
    );
    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state): void {}

  public function submitForm(array &$form, FormStateInterface $form_state): void {}

  public function submitBrowseRadius(array &$form, FormStateInterface $form_state): void {
    $km = (int) $form_state->getValue(['marketplace', 'radius', 'browse_radius_km']);
    BrowseRadius::set($this->appState, $km);
    $this->messenger()->addStatus($this->t('Search radius set to @km kilometers.', [
      '@km' => BrowseRadius::get($this->appState),
    ]));
    $form_state->setRebuild();
  }

  public function submitTriggerAction(array &$form, FormStateInterface $form_state): void {
    (new BoardTriggerActions())->handle($this->appState, $form_state, $this->client);
    $form_state->setRebuild();
  }

}
