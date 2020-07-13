<?php

declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    protected $clientApi;
    protected $client;
    protected $testCompany;

    public function setUp(): void
    {
        $this->clientApi = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
        $this->testCompany = $this->clientApi->createCompany([
            'name' => 'testCompany',
            'data_retention_period' => 5
        ]);

        $this->client = $this->clientApi->createClient($this->testCompany['uuid'], [
            'clientnumber'      => 2454987,
            'name'              => 'testClient',
            'initials'          => 't',
            'firstname'         => 'test',
            'insertion'         => 'ewf',
            'lastname'          => 'van tester',
            'street'            => 'testlaan',
            'housenumber'       => '22',
            'housenumberaddon'  => 'a',
            'postalcode'        => '3311YU',
            'city'              => 'Dordrecht',
            'country'           => 'Nederland',
            'mobile'            => '061785245856',
            'emailaddress'      => 'test@test.nl',
            'preferred_channel' => 'email',
        ]);
    }

    public function testCanUserSeeClients()
    {
        $this->assertCount(1, $this->clientApi->getClients($this->testCompany['uuid']));
    }

    public function testCanUserCreateClients()
    {
        $result = $this->clientApi->createClient($this->testCompany['uuid'], [
            'clientnumber'      => 2454987,
            'name'              => 'testClient2',
            'initials'          => 't2',
            'firstname'         => 'test2',
            'insertion'         => 'ewf2',
            'lastname'          => 'van tester2',
            'street'            => 'testlaan2',
            'housenumber'       => '222',
            'housenumberaddon'  => 'a2',
            'postalcode'        => '3311YU',
            'city'              => 'Dordrecht2',
            'country'           => 'Nederland2',
            'mobile'            => '0617852458562',
            'emailaddress'      => 'test@test.nl2',
            'preferred_channel' => 'email',
        ]);

        $this->assertEquals('testClient2', $result['name']);
        $this->assertCount(2, $this->clientApi->getClients($this->testCompany['uuid']));
    }
}