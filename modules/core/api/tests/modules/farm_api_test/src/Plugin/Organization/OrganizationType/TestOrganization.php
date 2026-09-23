<?php

declare(strict_types=1);

namespace Drupal\farm_api_test\Plugin\Organization\OrganizationType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\OrganizationType;
use Drupal\farm_entity\Plugin\Organization\OrganizationType\FarmOrganizationType;

/**
 * Provides the test organization type.
 */
#[OrganizationType(
  id: 'test',
  label: new TranslatableMarkup('Test'),
)]
class TestOrganization extends FarmOrganizationType {

}
