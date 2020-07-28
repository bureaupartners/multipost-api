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
        $result = $this->client->getMailboxReceivers(getenv('COMPANY_UUID'), 'ff56c164-5b2c-469a-9960-caf0ed4e9fea');

        $this->assertCount(1, $result);
    }

    public function testCanUserCreateReceiver()
    {
        $data = [
            'name'  => 'testReceiver',
        ];

        $result = $this->client->createMailboxReceiver(getenv('COMPANY_UUID'), 'ff56c164-5b2c-469a-9960-caf0ed4e9fea', $data);
        $this->assertEquals($data['name'], $result['body']['name']);
    }

    public function testCanUserDeleteReceiver()
    {
        $result = $this->client->deleteMailboxReceiver(getenv('COMPANY_UUID'), 'ab158d00-cfe9-11ea-a80f-e975bcfcb4d1', 'ab158d00-cfe9-11ea-a80f-e975bcfcb4d3');
        $this->assertEquals(true, $result['body']['success']);
    }
}