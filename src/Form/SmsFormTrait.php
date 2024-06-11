<?php

namespace Drupal\sevenapi\Form;

use Seven\Api\Resource\Sms\SmsConstants;

/** Defines a form that configures sevenapi settings. */
trait SmsFormTrait {
  public function getSmsForm($smsConfig, bool $open = FALSE): array {
    !$smsConfig && $smsConfig = [];

    return [
      'sms' => [
        '#open' => $open,
        '#title' => $this->t('SMS'),
        '#tree' => TRUE,
        '#type' => 'details',
        'text' => [
          '#attributes' => [
            'title' => $this->t('The default message to send.'),
          ],
          '#default_value' => $smsConfig['text'],
          '#title' => $this->t('Default Text'),
          '#type' => 'textarea',
        ],
        'signature' => [
          '#attributes' => [
            'title' => $this->t('Defines the default signature.'),
          ],
          '#default_value' => $smsConfig['signature'],
          '#title' => $this->t('Signature'),
          '#type' => 'textarea',
        ],
        'signaturePosition' => [
          '#default_value' => $smsConfig['signaturePosition'],
          '#options' => [
            'append' => $this->t('Append'),
            'prepend' => $this->t('Prepend'),
          ],
          '#title' => $this->t('Signature Position'),
          '#type' => 'radios',
        ],
        'performance_tracking' => [
          '#default_value' => (bool) $smsConfig['performance_tracking'],
          '#title' => $this->t('Performance Tracking'),
          '#type' => 'checkbox',
        ],
        'flash' => [
          '#default_value' => (bool) $smsConfig['flash'],
          '#title' => $this->t('Flash'),
          '#type' => 'checkbox',
        ],
        'to' => [
          '#default_value' => $smsConfig['to'],
          '#attributes' => [
            'title' => $this->t('The default recipient(s) separated by comma.'),
          ],
          '#title' => $this->t('To'),
          '#type' => 'textfield',
        ],
        'from' => [
          '#attributes' => [
            'title' => $this->t('Defines the default sender name.'),
          ],
          '#default_value' => $smsConfig['from'] ?? $this->config('system.site')
              ->get('name'),
          '#title' => $this->t('From'),
          '#type' => 'textfield',
        ],
        'delay' => [
          '#attributes' => [
            'title' => $this->t('The date and time for sending the message.'),
          ],
          '#default_value' => $smsConfig['delay'],
          '#title' => $this->t('Delay'),
          '#type' => 'datetime',
        ],
        'foreign_id' => [
          '#attributes' => [
            'title' => $this->t('The default foreign ID.'),
          ],
          '#default_value' => $smsConfig['foreign_id'],
          '#title' => $this->t('Foreign ID'),
          '#type' => 'textfield',
        ],
        'label' => [
          '#attributes' => [
            'title' => $this->t('The default message label.'),
          ],
          '#default_value' => $smsConfig['label'],
          '#title' => $this->t('Label'),
          '#type' => 'textfield',
        ],
        'udh' => [
          '#default_value' => $smsConfig['udh'],
          '#title' => $this->t('User Data Header'),
          '#type' => 'textfield',
        ],
        'ttl' => [
          '#attributes' => [
            'max' => SmsConstants::TTL_MAX,
            'min' => SmsConstants::TTL_MIN,
          ],
          '#default_value' => $smsConfig['ttl'],
          '#title' => $this->t('Time To Live'),
          '#type' => 'number',
        ],
      ],
    ];
  }

}
