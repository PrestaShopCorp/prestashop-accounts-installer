<?php

namespace PrestaShop\PsAccountsInstaller\Tests;

use Faker\Generator;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Generator
     */
    public $faker;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }
}
