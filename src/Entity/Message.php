<?php

namespace Drupal\sevenapi\Entity;

use Drupal\Core\Entity\Annotation\ContentEntityType;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\sevenapi\MessageInterface;
use Drupal\user\UserInterface;

/**
 * @ingroup sevenapi
 *
 * @ContentEntityType(
 *   id = "sevenapi_message",
 *   label = @Translation("Message"),
 *   admin_permission = "administer messages",
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "access" = "Drupal\sevenapi\AccessController",
 *     "list_builder" = "Drupal\sevenapi\Entity\Controller\MessageListBuilder",
 *     "form" = {
 *       "add" = "Drupal\sevenapi\Form\MessageDefaultForm",
 *       "delete" = "Drupal\sevenapi\Form\MessageDeleteForm"
 *     }
 *   },
 *   base_table = "sevenapi_message",
 *   entity_keys = {
 *     "id" = "id"
 *   },
 *   links = {
 *     "delete-form" = "/sevenapi/messages/manage/{message}/delete"
 *   },
 *   config_export = {
 *     "config",
 *     "id",
 *     "response"
 *   }
 * )
 */
class Message extends ContentEntityBase implements MessageInterface {

  /**
   * {@inheritdoc}
   * When a new entity instance is added, set the user_id entity reference to
   * the current user as the creator of the instance.
   */
  public static function preCreate(EntityStorageInterface $storage, array &$values) {
    parent::preCreate($storage, $values);

    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   * Define the field properties here.
   * Field name, type and size determine the table structure.
   * In addition, we can define how the field and its content can be manipulated
   * in the GUI. The behaviour of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Contact entity.'))
      ->setReadOnly(TRUE);

    // Owner field of the message.
    // Entity reference field, holds the reference to the user object.
    // The view shows the user name field of the user.
    // The form presents a auto complete field for the user name.
    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'author',
        'weight' => -3,
      ])
      ->setDisplayOptions('form', [
        'settings' => [
          'match_limit' => 10,
          'match_operator' => 'CONTAINS',
          'placeholder' => '',
          'size' => 60,
        ],
        'type' => 'hidden',
        'weight' => -3,
      ])
      ->setDisplayConfigurable('form', FALSE)
      ->setDisplayConfigurable('view', FALSE);

    $fields['sms'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Config'))
      ->setDescription(t('Message Configuration.'));

    $fields['response'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Response'))
      ->setDescription(t('Message Response.'));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    return $fields;
  }

  /** {@inheritdoc} */
  public function getOwner(): UserInterface {
    return $this->get('user_id')->entity;
  }

  /** {@inheritdoc} */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /** {@inheritdoc} */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /** {@inheritdoc} */
  public function setOwner(UserInterface $account) {
    return $this->set('user_id', $account->id());
  }

  public function getConfigSummary(): string {
    $cfg = $this->getJson('sms');
    $str = '';

    $boolFields = [
      'performance_tracking',
      'flash',
    ];

    foreach ($cfg as $k => $v) {
      if (in_array($k, $boolFields)) {
        if (TRUE === (bool) $v) {
          $str .= $k . ';';
        }
      }
      elseif (is_string($v) || is_numeric($v)) {
        $str .= "$k=$v;";
      }
    }

    return $str;
  }

  private function getJson(string $key) {
    $res = array_shift($this->values[$key]);
    $res = unserialize($res);
    return is_string($res) ? json_decode($res) : $res;
  }

  public function getResponseSummary(): string {
    /** @var \Seven\Api\Resource\Sms\Sms $res */
    $res = $this->getJson('response');
    $failed = 0;
    $messages = $res->getMessages();

    foreach ($messages as $msg) if (!$msg->isSuccess()) $failed++;
    $sent = is_array($messages) ? count($messages) : 0 - $failed;

    $str = 'success: ' . $res->getSuccess() . ' ;';
    $str .= ' price: ' . $res->getTotalPrice() . ' ;';
    $str .= ' balance: ' . $res->getBalance() . ' ; ';
    $str .= $sent . ' sent ; ';
    $str .= $failed . ' failed';

    return $str;
  }

}
