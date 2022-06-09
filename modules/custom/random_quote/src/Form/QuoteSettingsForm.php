<?php

namespace Drupal\random_quote\Form;

use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure random_quote settings for this site.
 *
 * @internal
 */
class QuoteSettingsForm extends ConfigFormBase {

    /**
     * The module handler.
     *
     * @var \Drupal\Core\Extension\ModuleHandlerInterface
     */
    protected $moduleHandler;

    /**
     * Constructs a \Drupal\random_quote\QuoteSettingsForm object.
     *
     * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
     *   The factory for configuration objects.
     * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
     *   The module handler.
     */
    public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler) {
        parent::__construct($config_factory);

        $this->moduleHandler = $module_handler;
    }

    /**
     * {@inheritdoc}
     */
    public static function create(ContainerInterface $container) {
        return new static(
        $container->get('config.factory'),
        $container->get('module_handler')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'random_quote_settings_form';
    }

    /**
     * {@inheritdoc}
     */
    protected function getEditableConfigNames() {
        return ['random_quote.settings'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $config = $this->config('random_quote.settings');

        $form['url'] = [
            '#type' => 'textfield',
            '#title' => $this->t('API URL'),
            '#description' => $this->t('Enter the URL.'),
            '#default_value' => \Drupal::config('random_quote.settings')->get('url'),
            '#required' => TRUE,
        ];
        $form['x_rapidapi_host'] = [
            '#type' => 'textfield',
            '#title' => $this->t('x_rapidapi_host'),
            '#description' => $this->t('Enter the Rapid API Host.'),
            '#default_value' => \Drupal::config('random_quote.settings')->get('x_rapidapi_host'),
            '#required' => TRUE,
        ];
        $form['x_rapidapi_key'] = [
            '#type' => 'textfield',
            '#title' => $this->t('x_rapidapi_key'),
            '#description' => $this->t('Enter the Rapid API Key.'),
            '#default_value' => \Drupal::config('random_quote.settings')->get('x_rapidapi_key'),
            '#required' => TRUE,
        ];

        $form['actions'] = [
            '#type' => 'actions',
        ];

        // Add a submit button that handles the submission of the form.
        $form['actions']['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Save'),
        ];

        return parent::buildForm($form, $form_state);
    }

    /**
     * Validate the title and the checkbox of the form.
     *
     * @param array $form
     *   The form.
     * @param \Drupal\Core\Form\FormStateInterface $form_state
     *   The form state.
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);

        if ($form_state->getValue('url') == '') {
            // Set an error for the form element with a key of "url".
            $form_state->setErrorByName('url', $this->t('The URL cannot be empty.'));
        }
        if ($form_state->getValue('x_rapidapi_host') == '') {
            // Set an error for the form element with a key of "x_rapidapi_host".
            $form_state->setErrorByName('x_rapidapi_host', $this->t('The Rapid API Host cannot be empty.'));
        }
        if ($form_state->getValue('x_rapidapi_key') == '') {
            // Set an error for the form element with a key of "x_rapidapi_key".
            $form_state->setErrorByName('x_rapidapi_key', $this->t('The Rapid API Key cannot be empty.'));
        }

    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::configFactory()->getEditable('random_quote.settings')->set('url', $form_state->getValue('url'))->save();
        \Drupal::configFactory()->getEditable('random_quote.settings')->set('x_rapidapi_host', $form_state->getValue('x_rapidapi_host'))->save();
        \Drupal::configFactory()->getEditable('random_quote.settings')->set('x_rapidapi_key', $form_state->getValue('x_rapidapi_key'))->save();
        $messenger = \Drupal::messenger();
        $messenger->addMessage('URL: ' . $form_state->getValue('url'));
        $messenger->addMessage('Rapid API Host: ' . $form_state->getValue('x_rapidapi_host'));
        $messenger->addMessage('Rapid API Key: ' . $form_state->getValue('x_rapidapi_key'));
    }

}
