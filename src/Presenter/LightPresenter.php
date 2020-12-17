<?php

namespace PrestaShop\PrestaShopAccountsInstaller\Presenter;

use PrestaShop\PrestaShopAccountsInstaller\Installer\Installer;

class LightPresenter {
    /**
     * @param string $psxName
     * @return array
     */
    public function Present($psxName)
    {
        $installer = new Installer;
        return [
            'psIs17' => $installer->isShopVersion17(),
            'psAccountsInstallLink' => $installer->getPsAccountsInstallLink($psxName),
            'psAccountsEnableLink' => null,
            'psAccountsIsInstalled' => $installer->isPsAccountsInstalled(),
            'psAccountsIsEnabled' => $installer->isPsAccountsEnabled(),
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
