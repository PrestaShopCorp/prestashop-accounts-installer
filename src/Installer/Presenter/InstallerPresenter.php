<?php

namespace PrestaShop\PsAccountsInstaller\Installer\Presenter;

use PrestaShop\PsAccountsInstaller\Installer\Installer;

class InstallerPresenter
{
    /**
     * @var Installer
     */
    private $installer;

    /**
     * InstallerPresenter constructor.
     *
     * @param Installer $installer
     */
    public function __construct(Installer $installer)
    {
        $this->installer = $installer;
    }

    /**
     * @param string $psxName
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function present($psxName)
    {
        // Fallback minimal Presenter
        return [
            'psIs17' => $this->installer->isShopVersion17(),
            'psAccountsInstallLink' => $this->installer->getPsAccountsInstallLink($psxName),
            'psAccountsEnableLink' => null,
            'psAccountsIsInstalled' => $this->installer->isPsAccountsInstalled(),
            'psAccountsIsEnabled' => $this->installer->isPsAccountsEnabled(),
            'onboardingLink' => null,
            'user' => [
                'email' => null,
                'emailIsValidated' => false,
                'isSuperAdmin' => \Context::getContext()->employee->isSuperAdmin(),
            ],
            'currentShop' => null,
            'shops' => [],
            'superAdminEmail' => null,
            'ssoResendVerificationEmail' => null,
            'manageAccountLink' => null,
        ];
    }
}
