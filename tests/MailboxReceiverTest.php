<?php
declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class MailboxReceiverTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserGetReceivers()
    {
        $result = $this->client->getMailboxReceivers('b31c7bc0-9387-11ea-9acf-d91057aa277f', 'ff56c164-5b2c-469a-9960-caf0ed4e9fea');

        $this->assertCount(1, $result);
    }

    public function testCanUserCreateReceiver()
    {
        $data = [
            'name'  => 'testReceiver',
        ];

        $result = $this->client->createMailboxReceiver('b31c7bc0-9387-11ea-9acf-d91057aa277f', 'ff56c164-5b2c-469a-9960-caf0ed4e9fea', $data);
        $this->assertEquals($data['name'], $result['body']['name']);
    }
}