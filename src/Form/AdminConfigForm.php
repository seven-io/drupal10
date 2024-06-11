<?php

namespace Drupal\sevenapi\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures sevenapi settings.
 */
class AdminConfigForm extends ConfigFormBase {

  use SmsFormTrait;
  use ConfigTrait;

  /** {@inheritdoc} */
  public function getFormId(): string {
    return 'sevenapi_admin_config';
  }

  /** {@inheritdoc} */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /** {@inheritdoc} */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $cfg = $this->getConfig()->get();

    $form['config'] = [
      '#default_tab' => 'edit-general',
      '#type' => 'vertical_tabs',
    ];

    $form['general'] = [
      '#group' => 'config',
      '#title' => $this->t('General'),
      '#tree' => TRUE,
      '#type' => 'details',
      'apiKey' => [
        '#attributes' => [
          'title' => $this->t("An API Key from seven.io is required for sending."),
        ],
        '#required' => '' === (string) $cfg['general']['apiKey'],
        '#size' => 96,
        '#title' => $this->t('API Key'),
        '#type' => 'password_confirm',
      ],
    ];

    $form += $this->getSmsForm($cfg['sms']);
    $form['sms']['#group'] = 'config';

    return $form;
  }

  /** {@inheritdoc} */
  public function submitForm(array &$form, FormStateInterface $state) {
    $values = $state->getValues();
    $general = $values['general'];
    $sms = $values['sms'];
    $cfg = $this->getConfig();

    empty($general['apiKey']) && $general['apiKey'] = $cfg->get('general.apiKey');

    $cfg->set('general', $general);
    $cfg->set('sms', $sms);

    $cfg->save();

    parent::submitForm($form, $state);
  }

}
