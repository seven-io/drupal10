<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS for Drupal 10/11</h1>

<p align="center">
  Send SMS, voice messages and run number lookups from <a href="https://www.drupal.org/">Drupal</a> via the seven gateway.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <img src="https://img.shields.io/badge/Drupal-10%20|%2011-blue" alt="Drupal 10 | 11" />
  <img src="https://img.shields.io/badge/PHP-8.1%2B-purple" alt="PHP 8.1+" />
  <a href="https://packagist.org/packages/seven.io/drupal10"><img src="https://img.shields.io/packagist/v/seven.io/drupal10" alt="Packagist" /></a>
</p>

> **Looking for Drupal 8?** Use [`seven-io/drupal8`](https://github.com/seven-io/drupal8). For the SMS Framework gateway, use [`seven-io/drupal-sms-framework`](https://github.com/seven-io/drupal-sms-framework).

---

## Features

- **Send SMS / Voice** - Single or bulk messages from the Drupal admin
- **Number Lookups** - HLR, MNP, CNAM and number-format lookups
- **Drupal-Native Forms** - Standard Drupal admin UI for sending and history

## Prerequisites

- Drupal 10 or 11
- PHP 8.1+
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

### Composer (recommended)

```bash
composer require seven.io/drupal10
drush en seven
```

### Manual

Download the [latest release](https://github.com/seven-io/drupal10/releases/latest), extract it into `web/modules/contrib/seven` and enable the module via **Extend** in the Drupal admin.

## Configuration

Open the seven configuration page in the Drupal admin, paste your API key and save.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/drupal10/issues).

## License

[MIT](LICENSE)
