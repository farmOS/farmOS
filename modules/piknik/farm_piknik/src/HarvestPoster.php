<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\Core\Database\Connection as DatabaseConnection;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\State\StateInterface;
use Drupal\farm_oauth_client\Connection;
use Drupal\farm_oauth_client\OAuthFlow;
use Drupal\farm_oauth_client\PinnedRequest;

/**
 * Runs automatic harvest triggers when a harvest log is marked done.
 */
final class HarvestPoster {

  public function __construct(
    private OAuthFlow $flow,
    private PinnedRequest $http,
    private Connection $connection,
    private StateInterface $state,
    private DatabaseConnection $database,
    private MessengerInterface $messenger,
  ) {}

  public function post(EntityInterface $log): void {
    $participant = $this->state->get('farm_piknik.participant_id', '');
    if (!is_string($participant) || $participant === '') {
      return;
    }
    $goods = HarvestPayload::farmGoodsName($log);
    // Auto triggers are recurring: fire per finished harvest, stay until the user removes them.
    $triggers = TriggerCatalog::autoForCrop($this->state, $goods);
    if ($triggers === []) {
      return;
    }
    $uuid = $log->uuid();
    $client = new ListingClient($this->flow, $this->http, $this->connection);
    foreach ($triggers as $trigger) {
      $key = $uuid . '|' . $trigger['id'];
      if ($this->alreadyPosted($key)) {
        continue;
      }
      try {
        $payload = HarvestPayload::fromTrigger($goods, $participant, $trigger);
        if ($payload === NULL) {
          $this->messenger->addWarning('A harvest trigger could not post. Check the farm goods name and place in setup.');
          continue;
        }
        $listing_id = $client->create($payload);
        // Dedup this harvest log only — do not delete the trigger from state.
        $this->database->merge('farm_piknik_post')
          ->key('log_uuid', $key)
          ->fields(['listing_id' => $listing_id, 'posted' => \Drupal::time()->getRequestTime()])
          ->execute();
        $this->messenger->addStatus('Harvest listing posted to Piknik.');
      }
      catch (\Throwable $e) {
        $this->messenger->addWarning($e->getMessage());
      }
    }
  }

  private function alreadyPosted(string $key): bool {
    $count = $this->database->select('farm_piknik_post', 'p')
      ->condition('log_uuid', $key)
      ->countQuery()
      ->execute()
      ->fetchField();
    return (int) $count > 0;
  }

}
