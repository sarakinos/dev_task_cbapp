<?php
// src/AppBundle/Tests/Util/CalculatorTest.php
namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\RedeemCodes;

class RedeemControllerTest extends WebTestCase
{
    public function testRedeemSuccessOrUsed()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/redeem');

        $result = $crawler->filter('html:contains("User")')->count();
        $expected = 0;

        $this->assertGreaterThan($expected, $result);
        // assert that your calculator added the numbers correctly!
        
    }
    public function testRedeemNotFound()
    {
        $client = static::createClient();
        $crawler = $client->request('GET','/redeem');

        $result = $crawler->filter('html:contains("Unable")')->count();
        $expected = 0;

        $this->assertGreaterThan($expected, $result);
        // assert that your calculator added the numbers correctly!
        
    }
}