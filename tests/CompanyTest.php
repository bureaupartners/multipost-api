<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }
    
    public function testCanUserSeeCompanies()
    {
        $this->assertCount(2, $this->client->getCompanies());
    }
}