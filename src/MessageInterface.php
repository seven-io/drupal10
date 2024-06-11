<?php

namespace Drupal\sevenapi;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface defining a Contact entity.
 * We have this interface, so we can join the other interfaces it extends.
 *
 * @ingroup sevenapi
 */
interface MessageInterface extends ContentEntityInterface, EntityOwnerInterface {

}
