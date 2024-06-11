<?php

namespace Drupal\sevenapi\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * @ingroup sevenapi
 */
class MessageListBuilder extends EntityListBuilder {
  public function buildHeader(): array {
    return [
        'config' => $this->t('Config'),
        'created' => $this->t('Created'),
        'response' => $this->t('Response'),
        'user_id' => $this->t('User ID'),
      ]
      + parent::buildHeader();
  }

  public function buildRow(EntityInterface $entity): array {
    return [
        'config' => $entity->getConfigSummary(),
        'created' => $entity->created->value,
        'response' => $entity->getResponseSummary(),
        'user_id' => $entity->user_id->value,
      ]
      + parent::buildRow($entity);
  }

}
