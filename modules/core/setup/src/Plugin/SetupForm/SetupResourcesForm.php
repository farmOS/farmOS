<?php

declare(strict_types=1);

namespace Drupal\farm_setup\Plugin\SetupForm;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_setup\Attribute\SetupForm;

/**
 * Resources step for the farmOS setup wizard.
 */
#[SetupForm(
  id: 'resources',
  title: new TranslatableMarkup('Next steps'),
  task_title: new TranslatableMarkup('Resources'),
  weight: 100,
)]
class SetupResourcesForm extends SetupFormBase {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // Documentation links.
    $form['docs'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Documentation'),
    ];
    $form['docs']['links'] = [
      '#theme' => 'item_list',
      '#items' => [
        $this->t('User guide: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/guide/']),
        $this->t('Data model <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/model/']),
      ],
    ];

    // Community links.
    $form['community'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Community Resources'),
    ];
    $form['community']['links'] = [
      '#theme' => 'item_list',
      '#items' => [
        $this->t('Community blog: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/blog/']),
        $this->t('Forum: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.discourse.group/']),
        $this->t('Chat room: <a href=":matrix-uri">#farmOS:matrix.org</a> / <a href=":irc-uri">#farmOS IRC</a>', [':matrix-uri' => 'https://app.element.io/#/room/#farmOS:matrix.org', ':irc-uri' => 'https://webchat.oftc.net/?channels=#farmOS']),
        $this->t('Monthly call: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/community/monthly-call/']),
      ],
    ];

    // Contributing links.
    $form['contributing'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Contributing'),
    ];
    $form['contributing']['links'] = [
      '#theme' => 'item_list',
      '#items' => [
        $this->t('Support requests: <a href=":uri">:uri</a>', [':uri' => 'https://farmos.discourse.group/new-topic?tags=support-request']),
        $this->t('Feature requests: <a href=":uri">:uri</a>', [':uri' => 'https://farmos.discourse.group/new-topic?category=development&tags=feature-request']),
        $this->t('Development guide: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/development/module/']),
        $this->t('Donate: <a href=":uri">:uri</a>', [':uri' => 'https://farmOS.org/donate/']),
      ],
    ];

    return $form;
  }

}
