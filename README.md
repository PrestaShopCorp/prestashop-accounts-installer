# prestashop-accounts-installer

Utility package to install `ps_accounts` module or present data to trigger manual install from psx configuration page.

This module also give you access to `ps_accounts` services through its module service container dealing with the installation status of the module.

## Installation

This package is available on [Packagist](https://packagist.org/packages/prestashop/prestashop-accounts-installer), 
you can install it via [Composer](https://getcomposer.org).

```shell script
composer require prestashop/prestashop-accounts-installer
```
## Setup your service container (optional)

Example :

```yaml
services:
  ps_accounts.installer:
    class: 'PrestaShop\PsAccountsInstaller\Installer\Installer'
    arguments:
      - '4.0.0'
```

## How to use it 

### Installer

In your module main class `install` method. (Will only do something on PrestaShop 1.7 and above)

```php
    define('PS_ACCOUNTS_VERSION', '4.0.0'); 

    (new \PrestaShop\PsAccountsInstaller\Installer\Installer(PS_ACCOUNTS_VERSION))->installPsAccounts();
```

OR

```php
    $this->getService('ps_accounts.installer')->installPsAccounts();
```

### Presenter

For example in your main module's class `getContent` method.

```php
    Media::addJsDef([
        'contextPsAccounts' => ((new \PrestaShop\PsAccountsInstaller\Installer\Installer(PS_ACCOUNTS_VERSION))
            ->getPsAccountsPresenter())
            ->Present($this->name),
    ]);
```
OR

```php
    Media::addJsDef([
        'contextPsAccounts' => $this->getService('ps_accounts.installer')
            ->getPsAccountsPresenter()
            ->present($this->name),
    ]);
```

This presenter will serve as default minimal presenter and switch to PsAccountsPresenter data when `ps_accounts` module is installed.

### Accessing PsAccounts Services

Installer class includes accessors to get instances of services from PsAccounts Module :

* getPsAccountsService
* getPsBillingService

The methods above will throw an exception in case `ps_accounts` module is not installed.

Example :

```php
use PrestaShop\PsAccountsInstaller\Installer\Exception\ModuleVersionException;
use PrestaShop\PsAccountsInstaller\Installer\Exception\ModuleNotInstalledException;
use PrestaShop\PsAccountsInstaller\Installer\Installer;

try {

    $psAccountsService = (new Installer(PS_ACCOUNTS_VERSION))->getPsAccountsService();
    
    // OR

    $psAccountsService = $this->getService('ps_accounts.installer')->getPsAccountsService();

    // Your code here

} catch (ModuleNotInstalledException $e) {

    // You handle exception here

} catch (ModuleVersionException $e) {

    // You handle exception here
}
```
