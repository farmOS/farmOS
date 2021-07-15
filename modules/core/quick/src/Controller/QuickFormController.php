<?php

namespace Drupal\farm_quick\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\farm_quick\QuickFormManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Quick form controller.
 */
class QuickFormController extends ControllerBase {

  use StringTranslationTrait;

  /**
   * The quick form manager.
   *
   * @var \Drupal\farm_quick\QuickFormManager
   */
  protected $quickFormManager;

  /**
   * Quick form controller constructor.
   */
  public function __construct(QuickFormManager $quick_form_manager) {
    $this->quickFormManager = $quick_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.quick_form'),
    );
  }

  /**
   * The index of quick forms.
   *
   * @return array
   *   Returns a render array.
   */
  public function index(): array {
    $quick_forms = $this->quickFormManager->getDefinitions();
    if (!empty($quick_forms)) {
      $items = [];
      foreach ($quick_forms as $quick_form) {
        $items[] = [
          'title' => $quick_form['label'],
          'description' => $quick_form['description'],
          'url' => Url::fromRoute('farm.quick.form', ['id' => $quick_form['id']]),
        ];
      }
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
