<?php

declare(strict_types=1);

namespace Drupal\farm_piknik;

use Drupal\farm_oauth_client\Connection;

/**
 * Normalizes Piknik listings for the board tables.
 */
final class ListingDirectory {

  public function __construct(
    private ListingClient $client,
    private Connection $connection,
  ) {}

  /**
   * @return list<array<string, mixed>>
   */
  public function mine(string $place_id): array {
    if ($place_id === '') {
      return [];
    }
    $owned = array_values(array_filter(
      $this->client->mine(),
      static fn(array $listing): bool => self::ownerPlaceId($listing) === $place_id,
    ));
    return $this->normalize($owned);
  }

  /**
   * Active nearby listings matching this farm's crops (with distance).
   *
   * @param list<string> $crops
   *
   * @return list<array<string, mixed>>
   */
  public function matching(array $crops, string $place_id, int $radius_km = BrowseRadius::DEFAULT): array {
    $from = $this->client->placeCoords($place_id);
    if ($from === NULL || $crops === []) {
      return [];
    }
    $own_ids = self::ownIds($this->client->mine());
    $seen = [];
    $cache = [];
    $rows = [];
    foreach (array_slice($crops, 0, 5) as $crop) {
      foreach ($this->client->nearby($from['lat'], $from['lng'], $crop, 15, $radius_km) as $listing) {
        $id = is_string($listing['id'] ?? NULL) ? $listing['id'] : '';
        $title = is_string($listing['title'] ?? NULL) ? $listing['title'] : '';
        if ($id === '' || isset($seen[$id]) || isset($own_ids[$id])) {
          continue;
        }
        if (self::ownerPlaceId($listing) === $place_id || !ListingRowMeta::titleMatches($title, $crop)) {
          continue;
        }
        $seen[$id] = TRUE;
        $row = $this->row($listing, $from, $cache);
        if ($row !== NULL) {
          $rows[] = $row;
        }
      }
    }
    return $rows;
  }

  /**
   * Active nearby listings from other places (with distance).
   *
   * @return list<array<string, mixed>>
   */
  public function nearbyOthers(string $place_id, int $limit = 25, int $radius_km = BrowseRadius::DEFAULT): array {
    $from = $this->client->placeCoords($place_id);
    if ($from === NULL) {
      return [];
    }
    $own_ids = self::ownIds($this->client->mine());
    $cache = [];
    $rows = [];
    foreach ($this->client->nearby($from['lat'], $from['lng'], '', $limit, $radius_km) as $listing) {
      $id = is_string($listing['id'] ?? NULL) ? $listing['id'] : '';
      if ($id === '' || isset($own_ids[$id]) || self::ownerPlaceId($listing) === $place_id) {
        continue;
      }
      $row = $this->row($listing, $from, $cache);
      if ($row !== NULL) {
        $rows[] = $row;
      }
    }
    return $rows;
  }

  /**
   * @param list<array<string, mixed>> $listings
   *
   * @return array<string, true>
   */
  private static function ownIds(array $listings): array {
    $ids = [];
    foreach ($listings as $listing) {
      if (is_string($listing['id'] ?? NULL) && $listing['id'] !== '') {
        $ids[$listing['id']] = TRUE;
      }
    }
    return $ids;
  }

  private static function ownerPlaceId(array $listing): string {
    if (is_string($listing['participantId'] ?? NULL)) {
      return $listing['participantId'];
    }
    if (is_array($listing['participant'] ?? NULL) && is_string($listing['participant']['id'] ?? NULL)) {
      return $listing['participant']['id'];
    }
    return '';
  }

  /**
   * @param list<array<string, mixed>> $listings
   *
   * @return list<array<string, mixed>>
   */
  private function normalize(array $listings): array {
    $rows = [];
    foreach ($listings as $listing) {
      $row = $this->row($listing);
      if ($row !== NULL) {
        $rows[] = $row;
      }
    }
    return $rows;
  }

  /**
   * @param array{lat: float, lng: float}|null $from
   * @param array<string, array{lat: float, lng: float}|null> $cache
   *
   * @return array<string, mixed>|null
   */
  private function row(array $listing, ?array $from = NULL, array &$cache = []): ?array {
    $title = is_string($listing['title'] ?? NULL) ? trim($listing['title']) : '';
    if ($title === '') {
      return NULL;
    }
    $status = is_string($listing['status'] ?? NULL) ? $listing['status'] : '';
    // Nearby tables only show active posts.
    if ($from !== NULL && $status !== 'active') {
      return NULL;
    }
    $id = is_string($listing['id'] ?? NULL) ? $listing['id'] : '';
    $type = is_string($listing['listingType'] ?? NULL) ? $listing['listingType'] : '';
    $participant = is_array($listing['participant'] ?? NULL) ? $listing['participant'] : [];
    $place = is_string($participant['name'] ?? NULL) ? trim($participant['name']) : '';
    $place_id = self::ownerPlaceId($listing);
    $ends = '';
    if (is_string($listing['endDate'] ?? NULL) && $listing['endDate'] !== '') {
      $ends = gmdate('Y-m-d', strtotime($listing['endDate']) ?: 0);
    }
    $base = $this->connection->baseUrl('piknik');
    return [
      'id' => $id,
      'title' => $title,
      'emoji' => ListingRowMeta::emoji($listing),
      'listing_url' => ListingWebUrl::forListing($base, $id),
      'type' => ListingRowMeta::typeLabel($type),
      'status' => $status !== '' ? $status : '—',
      'place' => $place !== '' ? $place : '—',
      'place_url' => PlaceWebUrl::forPlace(
        $base,
        $place_id,
        is_string($participant['participantType'] ?? NULL) ? $participant['participantType'] : '',
        is_string($participant['primaryRole'] ?? NULL) ? $participant['primaryRole'] : '',
      ),
      'ends' => $ends !== '' ? $ends : '—',
      'distance' => $from !== NULL ? $this->distanceLabel($from, $place_id, $cache) : '—',
    ];
  }

  /**
   * @param array{lat: float, lng: float} $from
   * @param array<string, array{lat: float, lng: float}|null> $cache
   */
  private function distanceLabel(array $from, string $place_id, array &$cache): string {
    if ($place_id === '') {
      return '—';
    }
    if (!array_key_exists($place_id, $cache)) {
      $cache[$place_id] = $this->client->placeCoords($place_id);
    }
    $to = $cache[$place_id];
    if ($to === NULL) {
      return '—';
    }
    return GeoDistance::label(GeoDistance::km($from['lat'], $from['lng'], $to['lat'], $to['lng']));
  }

}
