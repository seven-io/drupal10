<?php

namespace Drupal\sevenapi\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/** @ingroup sevenapi */
class MessageDeleteForm extends ContentEntityConfirmFormBase {

  /** {@inheritdoc} */
  public function getQuestion(): TranslatableMarkup {
    return $this->t('Are you sure you want to delete entity %id?', ['%id' => $this->entity->id()]);
  }

  /**
   * {@inheritdoc}
   * If the delete command is canceled, return to the contact list.
   */
  public function getCancelUrl(): Url {
    return new Url('entity.sevenapi_message.list');
  }

  /** {@inheritdoc} */
  public function getConfirmText(): TranslatableMarkup {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   * Delete the entity and log the event. logger() replaces the watchdog.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->delete();

    $this->logger('sevenapi')->notice('@type: deleted %title.',
      [
        '@type' => $this->entity->bundle(),
        '%title' => $this->entity->label(),
      ]);
    $form_state->setRedirect('entity.sevenapi_message.list');
  }

}
