<?php

namespace Drupal\my_statistics\Plugin\views\field;

use Drupal\views\Plugin\views\field\NumericField;
use Drupal\Core\Session\AccountInterface;

/**
 * Field handler to display numeric values from the my_statistics module.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("my_statistics_numeric")
 */
class StatisticsNumeric extends NumericField {

  /**
   * {@inheritdoc}
   */
  public function access(AccountInterface $account) {
    return $account->hasPermission('view post access counter');
  }

}
