<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "random_quote_example_block",
 *   admin_label = @Translation("Random quote block"),
 * )
 */
class RandomQuoteBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $result = \Drupal::service('random_quote.get_quote')->getQuote();

    $quote = $result['quote'];
    $character = $result['character'];
    $show = $result['show'];

    return [
      '#markup' =>  '<h2>Random movie quote</h2>'.
                    '<blockquote>'.$quote.'</blockquote>'.
                    '<p>Character: '.$character.'</p>'.
                    '<p>Show: '.$show.'</p>',
    ];
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIfHasPermission($account, 'access content');
  }

  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['random_quote_settings'] = $form_state->getValue('random_quote_settings');
  }
}