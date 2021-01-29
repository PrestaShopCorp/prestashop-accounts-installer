<?php

namespace PrestaShop\PsAccountsInstaller\Installer;

use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Addon\Module\ModuleManagerBuilder;
use PrestaShop\PsAccountsInstaller\Installer\Exception\InstallerException;
use PrestaShop\PsAccountsInstaller\Installer\Exception\ModuleNotInstalledException;
use PrestaShop\PsAccountsInstaller\Installer\Exception\ModuleVersionException;
use PrestaShop\PsAccountsInstaller\Installer\Presenter\InstallerPresenter;

class Installer
{
    const PS_ACCOUNTS_MODULE_NAME = 'ps_accounts';

    /**
     * Available services class names
     */
    const PS_ACCOUNTS_PRESENTER = 'PrestaShop\Module\PsAccounts\Presenter\PsAccountsPresenter';
    const PS_ACCOUNTS_SERVICE = 'PrestaShop\Module\PsAccounts\Service\PsAccountsService';
    const PS_BILLING_SERVICE = 'PrestaShop\Module\PsAccounts\Service\PsBillingService';

    /**
     * @var string required version
     */
    private $psAccountsVersion;

    /**
     * @var \Link
     */
    private $link;

    /**
     * Installer constructor.
     *
     * @param string $psAccountsVersion
     * @param \Link|null $link
     */
    public function __construct($psAccountsVersion, \Link $link = null)
    {
        $this->psAccountsVersion = $psAccountsVersion;

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

        return $moduleManager->install(self::PS_ACCOUNTS_MODULE_NAME);
    }

    /**
     * @return bool
     */
    public function isPsAccountsInstalled()
    {
        return \Module::isInstalled(self::PS_ACCOUNTS_MODULE_NAME)
            && $this->checkPsAccountsVersion();
    }

    /**
     * @return bool
     */
    public function isPsAccountsEnabled()
    {
        return \Module::isEnabled(self::PS_ACCOUNTS_MODULE_NAME);
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
     * @param string $psxName
     *
     * @return string | null
     *
     * @throws \PrestaShopException
     */
    public function getPsAccountsUpgradeLink($psxName)
    {
        if ($this->isShopVersion17()) {
            $router = SymfonyContainer::getInstance()->get('router');

            return \Tools::getHttpHost(true) . $router->generate('admin_module_manage_action', [
                    'action' => 'upgrade',
                    'module_name' => 'ps_accounts',
                ]);
        }

        return $this->getAdminLink('AdminModules', true, [], [
            'module_name' => $psxName,
            'configure' => $psxName,
            'upgrade' => 'ps_accounts',
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

        return \Tools::getShopDomainSsl(true)
            . __PS_BASE_URI__
            . basename(_PS_ADMIN_DIR_)
            . '/' . $this->link->getAdminLink($controller, $withToken)
            . $paramsAsString;
    }

    /**
     * @return bool
     */
    public function isShopVersion17()
    {
        return version_compare(_PS_VERSION_, '1.7.0.0', '>=');
    }

    /**
     * @return bool
     */
    public function checkPsAccountsVersion()
    {
        return version_compare(
            \Module::getInstanceByName(self::PS_ACCOUNTS_MODULE_NAME)->version,
            $this->psAccountsVersion,
            '>='
        );
    }

    /**
     * @param string $serviceName
     *
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    public function getService($serviceName)
    {
        if ($this->isPsAccountsInstalled()) {
            if ($this->checkPsAccountsVersion()) {
                return \Module::getInstanceByName(self::PS_ACCOUNTS_MODULE_NAME)
                    ->getService($serviceName);
            }
            throw new ModuleVersionException('Module version expected : ' . $this->psAccountsVersion);
        }
        throw new ModuleNotInstalledException('Module not installed : ' . self::PS_ACCOUNTS_MODULE_NAME);
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    public function getPsAccountsService()
    {
        return $this->getService(self::PS_ACCOUNTS_SERVICE);
    }

    /**
     * @return mixed
     *
     * @throws ModuleNotInstalledException
     * @throws ModuleVersionException
     */
    public function getPsBillingService()
    {
        return $this->getService(self::PS_BILLING_SERVICE);
    }

    /**
     * @return mixed
     */
    public function getPsAccountsPresenter()
    {
        try {
            return $this->getService(self::PS_ACCOUNTS_PRESENTER);
        } catch (InstallerException $e) {
            return new InstallerPresenter($this);
        }
    }
}
