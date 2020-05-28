<?php
declare (strict_types = 1);
use BureauPartners\MultiPost\Client;
use PHPUnit\Framework\TestCase;

class ShippingTest extends TestCase
{
    protected $client;

    public function setUp(): void
    {
        $this->client = new Client(getenv('EMAIL'), getenv('PASSWORD'), getenv('DOMAIN'));
    }

    public function testCanUserSeeShippingProducts()
    {
        $this->assertCount(1, $this->client->getShippingProducts());
    }

    public function testCanUserSeeShippingProduct()
    {
        $response = $this->client->getShippingProduct('2549dab0-943d-11ea-8f8a-0313e20b5a2d');

        $this->assertEquals('2821', $response['product_code']);
    }
}