<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\RedeemCodes;

class RedeemCodesTest extends \PHPUnit_Framework_TestCase {

	public function testUserId(){
		$code     = new RedeemCodes();
		$id       = 1;
		$code->setUserId($id);
		$expected = 1;

		$this->assertEquals($expected, $code->getUserId());
	}
	public function testCampainId(){
		$code     = new RedeemCodes();
		$id       = 1;
		$code->setCampainId($id);
		$expected = 1;

		$this->assertEquals($expected, $code->getCampainId());
	}
	public function testExpiration(){
		$code       = new RedeemCodes();
		$expiration = new \DateTime();
		$code->setExpiration($expiration);
		$expected = $expiration;

		$this->assertEquals($expected, $code->getExpiration());
	}
	public function testCode(){
		$code     = new RedeemCodes();
		$c        = 1;
		$expected = 1;
		$code->setCode($c);

		$this->assertEquals($expected, $code->getCode());
	}
	public function testUsedTrue(){
		$code     = new RedeemCodes();
		$used       = true;
		$code->setUsed($used);
		$expected = true;

		$this->assertTrue($code->getUsed());
	}
	public function testUsedFalse(){
		$code     = new RedeemCodes();
		$used       = false;
		$code->setUsed($used);
		$expected = false;

		$this->assertFalse($code->getUsed());
	}
}