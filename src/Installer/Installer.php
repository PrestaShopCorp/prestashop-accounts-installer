<?php

namespace PrestaShop\PrestaShopAccountsInstaller\Installer;

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;

class Installer {
    /**
     * @var string
     */
    private $psAccounts = 'ps_accounts';

    /**
     * @var SymfonyContainer
     */
    private $container;

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

        return $moduleManager->install($this->psAccounts);
    }

    /**
     * @return bool
     */
    public function isPsAccountsInstalled()
    {
        return \Module::isInstalled($this->psAccounts);
    }

    /**
     * @return bool
     */
    public function isPsAccountsEnabled()
    {
        return \Module::isEnabled($this->psAccounts);
    }

    /**
     * @param string $psxName
     * @return string | null
     *
     */
    public function getPsAccountsInstallLink($psxName)
    {
        if (true === $this->isPsAccountsInstalled()) {
            return null;
        }

        if ($this->isShopVersion17()) {
            $router = $this->get('router');

            return \Tools::getHttpHost(true) . $router->generate('admin_module_manage_action', [
                'action' => 'install',
                'module_name' => 'ps_accounts',
            ]);
        }

        return  $this->getAdminLink('AdminModules', true, [], [
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
     * @throws \PrestaShopException
     */
    public function getAdminLink($controller, $withToken = true, $sfRouteParams = [], $params = [])
    {
        if ($this->isShopVersion17()) {
            return $this->getAdminLink($controller, $withToken, $sfRouteParams, $params);
        }
        $paramsAsString = '';
        foreach ($params as $key => $value) {
            $paramsAsString .= "&$key=$value";
        }

        return \Tools::getShopDomainSsl(true) . __PS_BASE_URI__ . basename(_PS_ADMIN_DIR_) . '/' . $this->getAdminLink($controller, $withToken) . $paramsAsString;
    }

    /**
     * Override of native function to always retrieve Symfony container instead of legacy admin container on legacy context.
     *
     * @param string $serviceName
     *
     * @return mixed
     */
    public function get($serviceName)
    {
        if (null === $this->container) {
            $this->container = SymfonyContainer::getInstance();
        }

        return $this->container->get($serviceName);
    }

    /**
     * @return bool
     */
    public function isShopVersion17()
    {
        return version_compare(_PS_VERSION_, '1.7.0.0', '>=');
    }
}