<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class EnvelopeTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserGetEnvelopes()
    {
        $this->assertCount(1, $this->client->getEnvelopes('test'));
    }
}