<?php
declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class MailboxTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserGetMailboxes()
    {
        $result = $this->client->getMailboxes(getenv('COMPANY_UUID'));

        $this->assertCount(1, $result);
    }

    public function testCanUserCreateMailbox()
    {
        $data = [
            'name'  => 'testMailbox',
        ];

        $result = $this->client->createMailbox(getenv('COMPANY_UUID'), $data);
        $this->assertEquals($data['name'], $result['body']['name']);
    }

    public function testCanUserDeleteMailbox()
    {
        $result = $this->client->deleteMailbox(getenv('COMPANY_UUID'), 'ab158d00-cfe9-11ea-a80f-e975bcfcb4d1');

        $this->assertEquals(true, $result['body']['success']);
    }
}