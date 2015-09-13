<?php
use Symfony\Bundle\FrameworkBundle\Console\Application as App;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Command\GenRedeemCodesCommand;

class GenRedeemCodesCommandTest extends WebTestCase
{
    public function testSetUp()
    {
        $kernel = $this->createKernel();
        $kernel->boot();

        $application = new App($kernel);
        $application->add(new GenRedeemCodesCommand());

        $command = $application->find('populate:rcodes');
        $commandTester = new CommandTester($command);
        $commandTester->execute(
                array('command' => $command->getName(),
                        'campaign' => '1',
                        'codes_to_generate'=>5
            ));
        $this->assertContains("successful", $commandTester->getDisplay());         
    }
}