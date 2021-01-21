# prestashop-accounts-installer

Utility package to install `ps_accounts` module or present data to trigger manual install from psx configuration page.

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
  ps_accounts.presenter:
    class: 'PrestaShop\PsAccountsInstaller\Presenter\ContextPresenter'
```

## How to use it 

### Installer

In your module main class `install` method. (Only works on prestashop 1.7 and above)

```php
    (new \PrestaShop\PsAccountsInstaller\Installer\Installer())->installPsAccounts();
```

OR

```php
    $this->getService('ps_accounts.installer')->installPsAccounts();
```

### Presenter

For example in your main module's class `getContent` method.

```php
    Media::addJsDef([
        'contextPsAccounts' => (new \PrestaShop\PsAccountsInstaller\Presenter\ContextPresenter())
            ->Present($this->name),
    ]);
```
OR

```php
    Media::addJsDef([
        'contextPsAccounts' => $this->getService('ps_accounts.presenter')
            ->present($this->name),
    ]);
```

This presenter will serve as default presenter and switch to PsAccountsPresenter data when `ps_accounts` module is installed.

### Accessing PsAccounts Services

Installer class includes accessors to get instances of services from PsAccounts Module :

* getPsAccountsService
* getPsBillingService

The methods above will throw an exception in case `ps_accounts` module is not installed.

Example :

```php
try {

    $psAccountsService = (new Installer())->getPsAccountsService();
    
    // OR

    $this->getService('ps_accounts.installer')->getPsAccountsService();

    // Your code here

} catch (ModuleNotFoundException $e) {

    // You handle exception here
}
```
