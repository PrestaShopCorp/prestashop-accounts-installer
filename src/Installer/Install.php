<?php

namespace PrestaShop\PrestaShopAccountsInstaller\Installer;

use PrestaShop\Module\PsAccounts\Handler\ErrorHandler\ErrorHandler;
use PrestaShop\PrestaShopAccountsInstaller\Context\ShopContext;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

/**
 * Install ps_accounts module
 */
class Install {
    /**
     * @var string
     */
    private $psAccounts = 'ps_accounts';

    /**
     * Install ps_accounts module if not installed
     * Method to call in every psx modules during the installation process
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function installPsAccounts()
    {
        if (true === \Module::isInstalled($this->psAccounts)) {
            return true;
        }

        // if on PrestaShop 1.6, do nothing
        if (false === (new ShopContext())->isShop17()) {
            return true;
        }

        $moduleManagerBuilder = ModuleManagerBuilder::getInstance();
        $moduleManager = $moduleManagerBuilder->build();
        $moduleIsInstalled = $moduleManager->install($this->psAccounts);

        if (false === $moduleIsInstalled) {
            $errorHandler = ErrorHandler::getInstance();
            $errorHandler->handle(new \Exception('Module ps_accounts can\'t be installed', 500), 500);
        }

        return $moduleIsInstalled;
    }
}