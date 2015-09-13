<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Campaign;

class CampaignTest extends \PHPUnit_Framework_TestCase {

	public function testName(){
		$campaign = new Campaign();
		$name       = 'testName';
		$campaign->setName($name);
		$expected = 'testName';

		$this->assertEquals($expected, $campaign->getName());
	}
	public function testExpiration(){
		$campaign    = new Campaign();
		$expiration  = new \DateTime();
		$campaign->setExpiration($expiration);

		$this->assertEquals($expiration, $campaign->getExpiration());
	}
	public function testDuration(){
		$campaign    = new Campaign();
		$duration    = new \DateTime();
		$campaign->setDuration($duration);

		$this->assertEquals($duration, $campaign->getDuration());
	}
	public function testActiveTrue(){
		$campaign    = new Campaign();
		$active      = true;
		$campaign->setActive($active);

		$this->assertTrue($campaign->getActive());
	}
	public function testActiveFalse(){
		$campaign    = new Campaign();
		$active      = false;
		$campaign->setActive($active);

		$this->assertFalse($campaign->getActive());
	}
	public function testPrefix(){
		$campaign    = new Campaign();
		$prefix      = 'FB';
		$campaign->setPrefix($prefix);

		$this->assertEquals($prefix,$campaign->getPrefix());
	}
	public function testProjectNum(){
		$campaign    = new Campaign();
		$project_num = 1;
		$campaign->setProjectNum($project_num);

		$this->assertEquals($project_num,$campaign->getProjectNum());
	}
}