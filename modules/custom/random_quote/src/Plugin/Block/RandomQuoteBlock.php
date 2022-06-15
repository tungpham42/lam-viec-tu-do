<?php

namespace Drupal\random_quote\Plugin\Block;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Cache\UncacheableDependencyTrait;

use Drupal\random_quote\Service\QuoteInterface;

/**
 * Provides a block with a simple text.
 *
 * @Block(
 *   id = "random_quote_block",
 *   admin_label = @Translation("Random quote block"),
 * )
 */
class RandomQuoteBlock extends BlockBase implements ContainerFactoryPluginInterface {
  
  use UncacheableDependencyTrait;

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
    // Initialise the block data.
    $build = [];

    // Do NOT cache a page with this block on it.
    \Drupal::service('page_cache_kill_switch')->trigger();
    
    $json_response = $this->quoteService->getContentResponse();
    $result = json_decode($json_response, true);
    $quote = $result[$this->quoteService->getBodyName()];

    $build['content'] = [
      '#markup' => '<blockquote>'.$quote.'</blockquote>',
      '#cache' => [
        'max-age' => 0,
      ],
    ];
    return $build;
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