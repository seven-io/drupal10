<?php

namespace Drupal\sevenapi\Form;

use DateTime;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormStateInterface;
use Exception;
use Seven\Api\Client;
use Seven\Api\Resource\Sms\SmsParams;
use Seven\Api\Resource\Sms\SmsResource;

/**
 * Form controller for the message entity edit forms.
 * @ingroup sevenapi
 */
class MessageDefaultForm extends ContentEntityForm {

  use SmsFormTrait;
  use ConfigTrait;

  /** {@inheritdoc} */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form += $this->getSmsForm($this->getConfig()->get('sms'), TRUE);

    return $form;
  }

  /** {@inheritdoc} */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\sevenapi\Entity\Message $msg */
    $msg = $this->getEntity();
    $sms = $form_state->getValue('sms');
    $msg->set('sms', serialize($sms));
    $params = (new SmsParams($sms['text'], ...explode(',', $sms['to'])))
      ->setDelay(empty($sms['delay']) ? NULL : $sms['delay']->getPhpDateTime())
      ->setFlash($sms['flash'] ? true : null)
      ->setForeignId(empty($sms['foreign_id']) ? NULL : $sms['foreign_id'])
      ->setFrom(empty($sms['from']) ? NULL : $sms['from'])
      ->setLabel(empty($sms['label']) ? NULL : $sms['label'])
      ->setPerformanceTracking($sms['performance_tracking'] ? true : null)
      ->setTtl(empty($sms['ttl']) ? NULL : (int)$sms['ttl'])
      ->setUdh(empty($sms['udh']) ? NULL : $sms['udh'])
    ;
    $client = new Client($this->getConfig()->get('general.apiKey'), 'Drupal10');
    try {
      $res = (new SmsResource($client))->dispatch($params);
      $msg->set('response', serialize($res));
    } catch (Exception) {}

    try {
      $msg->save();
    } catch (EntityStorageException) {
    }

    $form_state->setRedirect('entity.sevenapi_message.list');
  }

  protected function actions(array $form, FormStateInterface $form_state): array {
    $actions = parent::actions($form, $form_state);

    $actions['submit']['#value'] = $this->t('Create Message');

    return $actions;
  }

}
