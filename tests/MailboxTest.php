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
        $result = $this->client->getMailboxes('b31c7bc0-9387-11ea-9acf-d91057aa277f');

        $this->assertCount(1, $result);
    }

    public function testCanUserCreateMailbox()
    {
        $data = [
            'name'  => 'testMailbox',
        ];

        $result = $this->client->createMailbox('b31c7bc0-9387-11ea-9acf-d91057aa277f', $data);
        $this->assertEquals($data['name'], $result['body']['name']);
    }
}