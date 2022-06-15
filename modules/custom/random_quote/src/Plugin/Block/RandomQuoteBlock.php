<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\random_quote\Service\QuoteInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "random_quote_example_block",
 *   admin_label = @Translation("Random quote block"),
 * )
 */
class RandomQuoteBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The storage for random_quote.
   *
   * @var \Drupal\random_quote\QuoteInterface
   */
  protected $quoteService;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, QuoteInterface $quote_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->quoteService = $quote_service;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('random_quote.get_quote')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $json_response = $this->quoteService->getContentResponse();
    $result = json_decode($json_response, true);
    $quote = $result[$this->quoteService->getBodyName()];

    return [
      '#markup' => '<blockquote>'.$quote.'</blockquote>',
      '#cache' => [
        'max-age' => 0,
      ],
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