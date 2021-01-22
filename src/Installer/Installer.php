<?php

namespace PrestaShop\PsAccountsInstaller\Installer;

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;
use PrestaShop\PsAccountsInstaller\Installer\Exception\ModuleNotFoundException;

class Installer
{
    const PS_ACCOUNTS = 'ps_accounts';

    /**
     * @var \Link
     */
    private $link;

    public function __construct(\Link $link = null)
    {
        if (null === $link) {
            $link = new \Link();
        }
        $this->link = $link;
    }

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
        if (true === $this->isPsAccountsInstalled()) {
            return true;
        }

        if (false === $this->isShopVersion17()) {
            return true;
        }

        $moduleManagerBuilder = ModuleManagerBuilder::getInstance();
        $moduleManager = $moduleManagerBuilder->build();

        return $moduleManager->install(self::PS_ACCOUNTS);
    }

    /**
     * @return bool
     */
    public function isPsAccountsInstalled()
    {
        return \Module::isInstalled(self::PS_ACCOUNTS);
    }

    /**
     * @return bool
     */
    public function isPsAccountsEnabled()
    {
        return \Module::isEnabled(self::PS_ACCOUNTS);
    }

    /**
     * @param string $psxName
     *
     * @return string | null
     *
     * @throws \PrestaShopException
     */
    public function getPsAccountsInstallLink($psxName)
    {
        if (true === $this->isPsAccountsInstalled()) {
            return null;
        }

        if ($this->isShopVersion17()) {
            $router = SymfonyContainer::getInstance()->get('router');

            return \Tools::getHttpHost(true) . $router->generate('admin_module_manage_action', [
                    'action' => 'install',
                    'module_name' => 'ps_accounts',
                ]);
        }

        return $this->getAdminLink('AdminModules', true, [], [
            'module_name' => $psxName,
            'configure' => $psxName,
            'install' => 'ps_accounts',
        ]);
    }

    /**
     * Adapter for getAdminLink from prestashop link class
     *
     * @param string $controller controller name
     * @param bool $withToken include or not the token in the url
     * @param array $sfRouteParams
     * @param array $params
     *
     * @return string
     *
     * @throws \PrestaShopException
     */
    public function getAdminLink($controller, $withToken = true, $sfRouteParams = [], $params = [])
    {
        if ($this->isShopVersion17()) {
            return $this->link->getAdminLink($controller, $withToken, $sfRouteParams, $params);
        }
        $paramsAsString = '';
        foreach ($params as $key => $value) {
            $paramsAsString .= "&$key=$value";
        }

        return \Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/' . $this->link->getAdminLink($controller, $withToken) . $paramsAsString;
    }

    /**
     * @return bool
     */
    public function isShopVersion17()
    {
        return version_compare(_PS_VERSION_, '1.7.0.0', '>=');
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotFoundException
     */
    public function getPsAccountsService()
    {
        if ($this->isPsAccountsInstalled()) {
            return \Module::getInstanceByName(self::PS_ACCOUNTS)
                ->getService('PrestaShop\Module\PsAccounts\Service\PsAccountsService');
        }

        throw new ModuleNotFoundException('Can\'t find module ps_accounts');
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotFoundException
     */
    public function getPsBillingService()
    {
        if ($this->isPsAccountsInstalled()) {
            return \Module::getInstanceByName(self::PS_ACCOUNTS)
                ->getService('PrestaShop\Module\PsAccounts\Service\PsBillingService');
        }

        throw new ModuleNotFoundException('Can\'t find module ps_accounts');
    }
}
