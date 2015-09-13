<?php
// src/AppBundle/Command/GenRedeemCodes.php
namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManager;

use AppBundle\Entity\RedeemCodes;

class GenRedeemCodesCommand extends ContainerAwareCommand
{
    protected $em;

    protected function configure()
    {        
        //Configure the command with its name and the required arguments
        $this
            ->setName('populate:rcodes')
            ->setDescription('Generate Redeem Codes')
            ->addArgument(
                'campaign',
                InputArgument::REQUIRED,
                'Desired campaign id?'
            )
            ->addArgument(
                'codes_to_generate',
                InputArgument::REQUIRED,
                'Codes to be generated?'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {      
        //Get the campaign id so we can extract the prefix from the database
        //Codes to generate var determines how many codes to be added , is used in the
        //bellow for loop
        $campaign_id = $input->getArgument('campaign');
        $codes_to_generate = $input->getArgument('codes_to_generate');
        
        $em = $this->getContainer()->get('doctrine')->getManager();
        $campaign_entity   = $em->getRepository('AppBundle:Campaign')->findById($campaign_id);   
        //If the campaign the user entered exists
        if($campaign_entity){
            for($i=0;$i<$codes_to_generate;$i++){
                $newCode = new RedeemCodes();

                //Generate a random string to add after the campaign prefix to the redeem code
                //The length is adjustable as it doesn't exceeds the lenght of the shuffle
                //By substr we get only the first $lenght characters of the shuffled string
                $length = 3;
                $randomString = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
                //Populating the new Redeem Code object and adding to database
                $newCode->setCampainId($campaign_entity[0]->getId());
                $newCode->setUserId(0);
                $newCode->setExpiration($campaign_entity[0]->getExpiration());            
                $newCode->setCode($campaign_entity[0]->getPrefix().$randomString);

                $em->persist($newCode);
            }
            $em->flush();
            $text = "Operation successful! $codes_to_generate redeem codes populated";

        }else{
            $text = "An error has occured";
        }
        //Return $text to the stdOut
        $output->writeln($text);

    }
}