<?php

namespace Tests\Unit\Support;

use PHPUnit\Framework\TestCase;

class StringHelperTest extends TestCase
{
    protected function setUp(): void
    {
        require __DIR__ .'/../../../app/Support/Helpers/StringHelper.php';

        parent::setUp();
    }

    public function testIsDateValid()
    {
        $dateValid = '2023-01-01';
        $this->assertTrue(true, isValidDate($dateValid));
    }

    public function testIsDateValidToInvalidDate()
    {
        $dateInvalid = '15/05/2023';
        $this->assertEquals(false, isValidDate($dateInvalid));
    }
}
