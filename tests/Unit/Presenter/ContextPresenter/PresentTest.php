<?php
/**
 * 2007-2020 PrestaShop and Contributors.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA <contact@prestashop.com>
 * @copyright 2007-2020 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 * International Registered Trademark & Property of PrestaShop SA
 */

namespace PrestaShop\PsAccountsInstaller\Tests\Unit\Presenter\ContextPresenter;

use PrestaShop\PsAccountsInstaller\Tests\TestCase;

class PresentTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldHaveCorrectPresenterStructure()
    {
        $fakePresenter = [
            'psIs17' => true,
            'psAccountsInstallLink' => $this->faker->url,
            'psAccountsEnableLink' => $this->faker->url,
            'psAccountsIsInstalled' => false,
            'psAccountsIsEnabled' => false,
            'onboardingLink' => $this->faker->url,
            'user' => [
                'email' => $this->faker->safeEmail,
                'emailIsValidated' => true,
                'isSuperAdmin' => true,
            ],
            'currentShop' => null,
            'shops' => [],
            'superAdminEmail' => $this->faker->safeEmail,
            'ssoResendVerificationEmail' => $this->faker->url,
            'manageAccountLink' => $this->faker->url,
        ];

        $this->assertArrayHasKey('psIs17', $fakePresenter, "Key 'psIs17' don't exist in Array");
        $this->assertInternalType('boolean', $fakePresenter['psIs17'], "'psIs17' isn't string");

        $this->assertArrayHasKey('psAccountsInstallLink', $fakePresenter, "Key 'psAccountsInstallLink' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['psAccountsInstallLink'], "'psAccountsInstallLink' isn't string");

        $this->assertArrayHasKey('psAccountsEnableLink', $fakePresenter, "Key 'psAccountsEnableLink' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['psAccountsEnableLink'], "'psAccountsEnableLink' isn't string");

        $this->assertArrayHasKey('psAccountsIsInstalled', $fakePresenter, "Key 'psAccountsIsInstalled' don't exist in Array");
        $this->assertInternalType('boolean', $fakePresenter['psAccountsIsInstalled'], "'psAccountsIsInstalled' isn't boolean");

        $this->assertArrayHasKey('psAccountsIsEnabled', $fakePresenter, "Key 'psAccountsIsEnabled' don't exist in Array");
        $this->assertInternalType('boolean', $fakePresenter['psAccountsIsEnabled'], "'psAccountsIsEnabled' isn't boolean");

        $this->assertArrayHasKey('onboardingLink', $fakePresenter, "Key 'onboardingLink' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['onboardingLink'], "'onboardingLink' isn't string");

        $this->assertArrayHasKey('user', $fakePresenter, "Key 'user' don't exist in Array");

        $this->assertArrayHasKey('email', $fakePresenter['user'], "Key 'email' don't exist in Array");

        $this->assertArrayHasKey('emailIsValidated', $fakePresenter['user'], "Key 'emailIsValidated' don't exist in Array");
        $this->assertInternalType('boolean', $fakePresenter['user']['emailIsValidated'], "'emailIsValidated' isn't boolean");

        $this->assertArrayHasKey('isSuperAdmin', $fakePresenter['user'], "Key 'isSuperAdmin' don't exist in Array");
        $this->assertInternalType('boolean', $fakePresenter['user']['isSuperAdmin'], "'isSuperAdmin' isn't boolean");

        $this->assertArrayHasKey('currentShop', $fakePresenter, "Key 'currentShop' don't exist in Array");
        $this->assertArrayHasKey('shops', $fakePresenter, "Key 'shops' don't exist in Array");

        $this->assertArrayHasKey('superAdminEmail', $fakePresenter, "Key 'superAdminEmail' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['superAdminEmail'], "'superAdminEmail' isn't string");

        $this->assertArrayHasKey('ssoResendVerificationEmail', $fakePresenter, "Key 'ssoResendVerificationEmail' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['ssoResendVerificationEmail'], "'ssoResendVerificationEmail' isn't string");

        $this->assertArrayHasKey('manageAccountLink', $fakePresenter, "Key 'manageAccountLink' don't exist in Array");
        $this->assertInternalType('string', $fakePresenter['manageAccountLink'], "'manageAccountLink' isn't string");
    }
}
