<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    protected $client;
    protected $countCompanies;
    protected $testCompany;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
        $this->testCompany = $this->client->createCompany([
            'name' => getenv('testCompanyName'),
            'data_retention_period' => 5
        ]);
        $this->countCompanies = count($this->client->getCompanies());
    }
    
    public function testCanUserSeeCompanies()
    {
        $this->assertCount($this->countCompanies, $this->client->getCompanies());
    }

    public function testCanUserCreateCompany()
    {
        $response = $this->client->createCompany([
            'name' => getenv('testCompanyName').'2',
            'parent_company' => $this->testCompany['uuid'],
            'data_retention_period' => 5
        ]);

        $checkCompanies = ($this->countCompanies + 1);

        $this->assertCount($checkCompanies, $this->client->getCompanies());
    }

    public function testCanUserSeeCompany()
    {
        $company = $this->client->getCompany($this->testCompany['uuid']);

        $this->assertEquals('testCompany', $company['name']);
    }

    public function testCanUserDeleteCompany()
    {
        $this->client->deleteCompany($this->testCompany['uuid']);

        $checkCompanies = ($this->countCompanies - 1);

        $this->assertCount($checkCompanies, $this->client->getCompanies());
    }

    public function testCanUserSeeUsersInCompany()
    {
        $this->assertCount(1, $this->client->listUsers($this->testCompany['uuid']));
    }

    public function testCanUserAddUser()
    {
        $result = $this->client->addUser($this->testCompany['uuid'], [
            'users' => [0 => '78e6b040-799c-11ea-abf1-8db850624fbe']
        ]);
        $this->assertCount(2, $this->client->listUsers($this->testCompany['uuid']));
    }
}