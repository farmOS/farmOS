<?php

namespace Drupal\farm_quick\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\farm_quick\QuickFormInstanceManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Quick form controller.
 */
class QuickFormController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * The quick form instance manager.
   *
   * @var \Drupal\farm_quick\QuickFormInstanceManagerInterface
   */
  protected $quickFormInstanceManager;

  /**
   * Quick form controller constructor.
   *
   * @param \Drupal\farm_quick\QuickFormInstanceManagerInterface $quick_form_instance_manager
   *   The quick form instance manager.
   */
  public function __construct(QuickFormInstanceManagerInterface $quick_form_instance_manager) {
    $this->quickFormInstanceManager = $quick_form_instance_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('quick_form.instance_manager'),
    );
  }

  /**
   * The index of quick forms.
   *
   * @return array
   *   Returns a render array.
   */
  public function index(): array {
    /** @var \Drupal\farm_quick\Entity\QuickFormInstanceInterface[] $quick_forms */
    $quick_forms = $this->quickFormInstanceManager->getInstances();
    $items = [];
    foreach ($quick_forms as $id => $quick_form) {
      $url = Url::fromRoute('farm.quick.' . $id);
      if ($url->access()) {
        $items[] = [
          'title' => $quick_form->getLabel(),
          'description' => $quick_form->getDescription(),
          'url' => $url,
        ];
      }
    }
    if (!empty($items)) {
      $output = [
        '#theme' => 'admin_block_content',
        '#content' => $items,
      ];
    }
    else {
      $output = [
        '#markup' => $this->t('You do not have any quick forms.'),
      ];
    }
    return $output;
  }

}
