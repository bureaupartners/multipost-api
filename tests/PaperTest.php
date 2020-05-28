<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class PaperTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserSeePaperTypes()
    {
        $this->assertCount(10, $this->client->getPaperTypes());
    }

    public function testCanUserSeePaperType()
    {
        $getPaper = $this->client->getPaperType('78d13400-799c-11ea-b25d-71e7ccf0249b');

        $this->assertEquals('A4', $getPaper['name']);
    }
}