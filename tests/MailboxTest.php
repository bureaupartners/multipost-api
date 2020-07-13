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

    public function testCanUserSeeMailboxes()
    {
        $result = $this->client->getMailboxes('e23296b0-c4f1-11ea-9834-295ab019a7f7');
        $this->assertCount(1, $result);
    }

    public function testCanUserSeeMailbox()
    {
        $result = $this->client->getMailbox('e23296b0-c4f1-11ea-9834-295ab019a7f7', 'efwwfewefefewwfewef');
        $this->assertEquals('test_box', $result['name']);
    }

}