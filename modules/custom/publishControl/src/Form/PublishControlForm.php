<?php

namespace Drupal\publishControl\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * PublishControlForm controller.
 */
class PublishControlForm extends FormBase {

  /**
   * Returns a unique string identifying the form.
   *
   * The returned ID should be a unique string that can be a valid PHP function
   * name, since it's used in hook implementation names such as
   * hook_form_FORM_ID_alter().
   *
   * @return string
   *   The unique string identifying the form.
   */
  public function getFormId() {
    return 'publishControl_form';
  }

  /**
   * Form constructor.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array
   *   The form structure.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $nids = \Drupal::entityQuery('node')->condition('type','article')->execute();
    $nodes =  Node::loadMultiple($nids);
    $node_options = array();

    foreach ($nodes as $node) {
      $node_options[$node->id()] = $node->getTitle();
    }

    $form['selected_article'] = array(
      '#title' => t('Choose an article.'),
      '#type' => 'select',
      '#description' => '',
      '#options' => $node_options,
    );

    $form['article_published'] = array(
      '#title' => t('Choose published status.'),
      '#type' => 'select',
      '#description' => '',
      '#default_value' => 'Change status',
      '#options' => array('Change status', 'Published', 'Unpublished'),
    );

    $form['article_sticky'] = array(
      '#title' => t('Choose sticky status.'),
      '#type' => 'select',
      '#description' => '',
      '#default_value' => 'Change status',
      '#options' => array('Change status', 'Sticky', 'Unsticky'),
    );

    // Group submit handlers in an actions element with a key of "actions" so
    // that it gets styled correctly, and so that other modules may add actions
    // to the form. This is not required, but is convention.
    $form['actions'] = [
      '#type' => 'actions',
    ];

    // $form['actions']['submit'] = [
    //   '#type' => 'submit',
    //   '#value' => $this->t('Update'),
    // ];

    $form['actions']['update'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update'),
      '#submit' => array('::updateArticle'),
    ];
    $form['actions']['delete'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#attributes' => array('onclick' => 'if(!confirm("Really Delete?")){return false;}'),
      '#submit' => array('::deleteArticle'),
    ];

    return $form;

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

  }

  /**
   * Form submission handler.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // $selected_article = Node::load($form_state->getValue('selected_article') + 1);
    // $selected_article->setPublished(false);
    // if ($form_state->getValue('article_published') == 1) {
    //   $selected_article->setPublished(true);
    // } else if ($form_state->getValue('article_published') == 2) {
    //   $selected_article->setPublished(false);
    // }
    // $messenger = \Drupal::messenger();
    // $messenger->addMessage('Status: ' . $form_state->getValue('article_published'));
    // $messenger->addMessage('Selected article: ' . $selected_article->getTitle());
  }

  public function updateArticle(array &$form, FormStateInterface $form_state) {
    $selected_article = Node::load($form_state->getValue('selected_article'));
    if ($form_state->getValue('article_published') == 1) {
      $selected_article->setPublished(true)->save();
    } else if ($form_state->getValue('article_published') == 2) {
      $selected_article->setPublished(false)->save();
    }
    if ($form_state->getValue('article_sticky') == 1) {
      $selected_article->setSticky(true)->save();
    } else if ($form_state->getValue('article_sticky') == 2) {
      $selected_article->setSticky(false)->save();
    }
    $messenger = \Drupal::messenger();
    $messenger->addMessage('Selected article: ' . $selected_article->getTitle());
    $messenger->addMessage('Published status: ' . $form_state->getValue('article_published'));
    $messenger->addMessage('Sticky status: ' . $form_state->getValue('article_sticky'));
  }

  public function deleteArticle(array &$form, FormStateInterface $form_state) {
    $selected_article = Node::load($form_state->getValue('selected_article'));
    if ($selected_article) {
      $selected_article->delete();
    }
  }
}