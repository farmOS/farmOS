<?php

namespace Drupal\farm_ui_map\Controller;

use Drupal\asset\Entity\AssetInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Returns response for the map popup.
 */
class MapPopup extends ControllerBase {

  /**
   * Set the display mode used.
   *
   * @var string
   */
  protected string $displayMode = 'map_popup';

  /**
   * Display an asset entity standalone in the map popup.
   *
   * @param \Drupal\asset\Entity\AssetInterface $asset
   *   The asset entity.
   *
   * @return \Symfony\Component\HttpFoundation\Response
   *   The response to display in the popup.
   */
  public function display(AssetInterface $asset) : Response {
    $response = new Response();

    $view_builder = $this->entityTypeManager()->getViewBuilder($asset->getEntityTypeId());
    $build = $view_builder->view($asset, $this->displayMode);
    // Render already exposes cache metadata, so no need to do it twice.
    $response->setContent(render($build));
    return $response;
  }

}
