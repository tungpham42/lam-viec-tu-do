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
 *   admin_label = @Translation("Randome quote block"),
 * )
 */
class RandomQuoteBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $url = "https://movies-quotes.p.rapidapi.com/quote";
    $client = \Drupal::httpClient();
    $response = $client->request('GET', $url, [
      'headers' => [
        'X-RapidAPI-Host' => 'movies-quotes.p.rapidapi.com',
        'X-RapidAPI-Key' => 'OvEezA3997msh66qZgNJ66YsAWs0p13mlOMjsnO4L2P0BG7sM4',
      ]
    ]);
    $body = $response->getBody()->getContents();
    $status = $response->getStatusCode();
    
    $body_result = json_decode($body, true);

    $quote = $body_result['quote'];
    $character = $body_result['character'];
    $show = $body_result['show'];

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