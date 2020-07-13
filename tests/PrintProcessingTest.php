<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class PrintProcessingTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserSeePrintProcessingTypes()
    {
        $result = $this->client->getPrintProcessingTypes();
        $this->assertCount(16, $result);
    }

    public function testCanUserSeePrintProcessingType()
    {
        $result = $this->client->getPrintProcessingType('78d2de80-799c-11ea-94e3-a1314fbacc26');
        $this->assertEquals('Scoring rule', $result['name']);
    }
}