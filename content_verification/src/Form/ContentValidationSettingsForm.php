<?php

namespace Drupal\content_verification\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form for Content Verification module.
 */
class ContentValidationSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['content_verification.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'content_verification_content_validation_settings';
  }

  /**
   * Build the configuration form.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Load existing configuration.
    $config = $this->config('content_verification.settings');

    // Field for setting minimum word count.
    $form['min_word_count'] = [
      '#type' => 'number',
      '#title' => $this->t('Minimum word count'),
      '#description' => $this->t('Set the minimum number of words required for content validation.'),
      '#default_value' => $config->get('min_word_count') ?? 300,
      '#min' => 1,
    ];

    // Field for selecting Flesch-Kincaid readability scale.
    $form['flesch_kincaid_scale'] = [
      '#type' => 'select',
      '#title' => $this->t('Flesch-Kincaid Readability Scale'),
      '#options' => [
        '90-100' => $this->t('90-100: Very easy to read. Best for children.'),
        '60-70' => $this->t('60-70: Easy to read. Best for teens and adults.'),
        '30-50' => $this->t('30-50: Fairly difficult to read. Best for advanced readers.'),
        '0-30' => $this->t('0-30: Very difficult to read. Suitable for technical or specialized texts.'),
      ],
      '#default_value' => $config->get('flesch_kincaid_scale') ?? '60-70',
      '#description' => $this->t('Select the Flesch-Kincaid readability scale to be used for articles.'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * Submit handler for saving the configuration.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save configuration settings.
    $this->config('content_verification.settings')
      ->set('min_word_count', $form_state->getValue('min_word_count'))
      ->set('flesch_kincaid_scale', $form_state->getValue('flesch_kincaid_scale'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}