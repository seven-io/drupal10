<?php

namespace Drupal\sevenapi\Form;

use Drupal\Core\Config\Config;

/** Defines a form that configures sevenapi settings. */
trait ConfigTrait {

  private string $_editableConfigName = 'sevenapi.settings';

  protected function getEditableConfigNames(): array {
    return [
      $this->_editableConfigName,
    ];
  }

  protected function getConfig(): Config {
    return $this->config($this->_editableConfigName);
  }

}
