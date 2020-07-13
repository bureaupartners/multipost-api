<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class EnvelopeTest extends TestCase
{
    protected $client;
    protected $testCompany;
    protected $testEnvelope;
    protected $testOrder;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
        $this->testCompany = $this->client->createCompany([
            'name' => 'testCompany',
            'data_retention_period' => 5
        ]);
        $this->testEnvelope = $this->client->createEnvelope($this->testCompany['uuid'], 'test envelope', 'test description');
        $this->testOrder = $this->client->orderEnvelope($this->testCompany['uuid'], $this->testEnvelope['uuid'], 100);
    }

    public function testCanUserGetEnvelopes()
    {
        $result = $this->client->getEnvelopes($this->testCompany['uuid']);

        $this->assertCount(1, $result);
    }

    public function testCanUserGetEnvelope()
    {
        $result = $this->client->getEnvelope($this->testCompany['uuid'], $this->testEnvelope['uuid']);
        $this->assertEquals('test envelope', $result['name']);
    }

    public function testCanUserCreateEnvelope()
    {
        $result = $this->client->createEnvelope($this->testCompany['uuid'], 'test envelope 2', 'test description 2');

        $this->assertEquals('test envelope 2', $result['name']);
    }

    public function testCanUserDeleteEnvelope()
    {
        $result = $this->client->deleteEnvelope($this->testCompany['uuid'], $this->testEnvelope['uuid']);

        $this->assertEquals(true, $result['success']);
    }

    public function testCanUserOrderEnvelopes()
    {
        $result = $this->client->orderEnvelope($this->testCompany['uuid'], $this->testEnvelope['uuid'], 100);

        $this->assertEquals(100, $result['quantity']);
    }

    public function testCanUserSeeOrderEnvelopes()
    {
        $result = $this->client->getEnvelopeOrder($this->testCompany['uuid'], $this->testOrder['uuid']);

        $this->assertEquals(100, $result['amount']);
    }
}