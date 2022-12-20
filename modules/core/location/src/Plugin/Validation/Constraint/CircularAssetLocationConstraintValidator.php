<?php

namespace Drupal\farm_location\Plugin\Validation\Constraint;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\farm_location\AssetLocationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the CircularAssetLocation constraint.
 */
class CircularAssetLocationConstraintValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * Asset location service.
   *
   * @var \Drupal\farm_location\AssetLocationInterface
   */
  protected $assetLocation;

  /**
   * CircularAssetLocationConstraintValidator constructor.
   *
   * @param \Drupal\farm_location\AssetLocationInterface $asset_location
   *   Asset location service.
   */
  public function __construct(AssetLocationInterface $asset_location) {
    $this->assetLocation = $asset_location;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('asset.location'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($value, Constraint $constraint) {
    /** @var \Drupal\Core\Field\EntityReferenceFieldItemList $value */
    /** @var \Drupal\farm_location\Plugin\Validation\Constraint\CircularAssetLocationConstraint $constraint */

    // Get the log that this field is on.
    $log = $value->getParent()->getValue();

    // If the log is not a movement, we have nothing to validate.
    if (empty($log->get('is_movement')->value)) {
      return;
    }

    // Get the locations(s) that asset(s) are being moved to.
    $locations = $log->get('location')->referencedEntities();

    // If there are no locations, we have nothing to validate.
    if (empty($locations)) {
      return;
    }

    // Get the log's timestamp.
    $timestamp = $log->get('timestamp')->value;

    // Iterate through referenced entities.
    foreach ($value->referencedEntities() as $delta => $asset) {

      // Load assets that are located in the asset being referenced.
      // Use our own method to recurse into sub-location assets as well.
      $assets_in_location = $this->getAssetsByLocationRecursively($asset, $timestamp);

      // Make sure that none of the assets are located in this asset.

      // Iterate through the locations and check for violations.
      $violation = FALSE;
      foreach ($locations as $location) {

        // Make sure that the asset and location are not the same.
        if ($location->id() == $asset->id()) {
          $violation = TRUE;
        }

        // Make sure that none of the assets are located in this asset.
        foreach ($assets_in_location as $asset_in_location) {
          if ($location->id() == $asset_in_location->id()) {
            $violation = TRUE;
            break;
          }
        }
      }

      // If a violation was found, flag it.
      if ($violation) {
        $this->context->buildViolation($constraint->message, ['%asset' => $asset->label()])
          ->atPath((string) $delta . '.target_id')
          ->setInvalidValue($asset->id())
          ->addViolation();
      }
    }
  }

  /**
   * Recursively get all assets in a location based on movement logs.
   *
   * @param \Drupal\asset\Entity\AssetInterface $location
   *   The location asset.
   * @param int $timestamp
   *   Include logs with a timestamp less than or equal to this.
   *
   * @return \Drupal\asset\Entity\AssetInterface[]
   *   An array of assets in the location.
   */
  private function getAssetsByLocationRecursively(AssetInterface $location, int $timestamp) {

    // This should never limit itself to assets with `is_location` property
    // set to TRUE, because that property can change. And changes to it are on
    // the asset itself, not on the log that we're validating here. This means
    // that if we only checked `is_location` assets here, they could potentially
    // be changed later, making it possible for a "valid" log to become
    // "invalid" without editing it. In order to thoroughly prevent circular
    // asset location we need to be able to be able to find assets that are
    // "located" in both location and non-location assets.
    // @see Drupal\farm_location\AssetLocation::getAssetsByLocation()

    // Get assets in this location.
    $assets = $this->assetLocation->getAssetsByLocation([$location], $timestamp);

    // Recurse into each asset to get any other assets located in each.
    foreach ($assets as $asset) {
      $assets += $this->getAssetsByLocationRecursively($asset, $timestamp);
    }

    // Return assets.
    return $assets;
  }

}
