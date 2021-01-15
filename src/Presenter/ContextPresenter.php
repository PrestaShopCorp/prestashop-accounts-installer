<?php

namespace PrestaShop\PsAccountsInstaller\Presenter;

use Module;
use PrestaShop\Module\PsAccounts\Presenter\PsAccountsPresenter;
use PrestaShop\PsAccountsInstaller\Installer\Installer;

class ContextPresenter {
    /**
     * @param string $psxName
     *
     * @return array
     *
     * @throws \PrestaShopException
     * @throws \Throwable
     */
    public function present($psxName)
    {
        $installer = new Installer;

        if ($installer->isPsAccountsInstalled()) {
            /** @var PsAccountsPresenter $presenter */
            $presenter = Module::getInstanceByName('ps_accounts')
                ->getService(PsAccountsPresenter::class);

            return $presenter->present($psxName);
        }

        // Fallback minimal Presenter
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
