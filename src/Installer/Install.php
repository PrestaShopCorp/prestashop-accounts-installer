<?php

namespace PrestaShop\PrestaShopAccountsInstaller\Installer;

/**
 * Install ps_accounts module
 */
class Install {
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
    public function installPsAccounts(): bool
    {
        if (true === $this->isPsAccountsInstalled()) {
            return true;
        }

        // if on PrestaShop 1.6, do nothing
        if (false === $this->isShopVersion17()) {
            return true;
        }

        $moduleManagerBuilder = \PrestaShop\Module\Ps_metrics\Module\ModuleManagerBuilder::getInstance();
        $moduleManager = $moduleManagerBuilder->build();
        $moduleIsInstalled = $moduleManager->install($this->psAccounts);

        //if (false === $moduleIsInstalled) {
        //    $errorHandler = ErrorHandler::getInstance();
        //    $errorHandler->handle(new \Exception('Module ps_accounts can\'t be installed', 500), 500);
        //}

        return $moduleIsInstalled;
    }

    /**
     * @return bool
     */
    public function isPsAccountsInstalled(): bool
    {
        return \Module::isInstalled($this->psAccounts);
    }

    /**
     * @return bool
     */
    public function isPsAccountsEnabled(): bool
    {
        return \Module::isEnabled($this->psAccounts);
    }

    /**
     * @param string $psxName
     * @return array
     */
    public function PresentLight(string $psxName): array
    {
        //try {
            return [
                'psIs17' => $this->isShopVersion17(),
                'psAccountsInstallLink' => $this->getPsAccountsInstallLink($psxName),
                'psAccountsEnableLink' => null,
                'psAccountsIsInstalled' => $this->isPsAccountsInstalled(),
                'psAccountsIsEnabled' => $this->isPsAccountsEnabled(),
                'onboardingLink' => null,
                'user' => [
                    'email' => null,
                    'emailIsValidated' => false,
                    'isSuperAdmin' => false,
                ],
                'currentShop' => null,
                'shops' => [],
                'superAdminEmail' => null,
                'ssoResendVerificationEmail' => null,
                'manageAccountLink' => null,
            ];
        //} catch (\Exception $e) {
        //    $this->getService(ErrorHandler::class)
        //        ->handle($e, $e->getCode());
        //}

        //return [];

    }

    /**
     * @param string $psxName
     * @return string | null
     *
     */
    public function getPsAccountsInstallLink(string $psxName): ?string
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
    public function getAdminLink(string $controller, $withToken = true, $sfRouteParams = [], $params = []): string
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
    public function get(string $serviceName)
    {
        if (null === $this->container) {
            $this->container = \PrestaShop\PrestaShop\Adapter\SymfonyContainer::getInstance();
        }

        return $this->container->get($serviceName);
    }

    /**
     * @return bool
     */
    private function isShopVersion17()
    {
        return version_compare(_PS_VERSION_, '1.7.0.0', '>=');
    }
}