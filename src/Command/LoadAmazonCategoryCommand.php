<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\AmazonCategory;
use Doctrine\ORM\EntityManagerInterface;

class LoadAmazonCategoryCommand extends Command
//class LoadAmazonCategoryCommand extends ContainerAwareCommand 
{
    private $em; 

    public function __construct($name = null, EntityManagerInterface $em) { 
        parent::__construct($name); 

        $this->em = $em;
    } 
    
    protected static $defaultName = 'app:load-amazon-category';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        //$io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        
        $amazonCategories=array(
            array(
                'id'=>'1261',
                'name'=>'Baby',
            ),
            array(
                'id'=>'268',
                'name'=>'Books',
            ),
            array(
                'id'=>'27432',
                'name'=>'Cameras & Photo',
            ),
            array(
                'id'=>'42428',
                'name'=>'Cell Phones & Accessories',
            ),
            array(
                'id'=>'312',
                'name'=>'Clothing, Shoes & Accessories',
            ),
        );
        
        //$amazonCategory=new AmazonCategory();
        
        $writedNewCategories=false;
        
        foreach ($amazonCategories as $category){
            //$category
            //dump($category['id']);
            
            //->getDoctrine()
            $amazonCategoryFromDb = $this->em->getRepository(AmazonCategory::class)
            //->find($category['id']);
            ->findOneBy(['id' => $category['id']]);
            
            //dump($amazonCategoryFromDb);
            
            if(empty($amazonCategoryFromDb)){
                $io->success('Hello 34544_1');
                
                $amazonCategory=new AmazonCategory();
                $amazonCategory->setId($category['id']);
                $amazonCategory->setName($category['name']);
                $this->em->persist($amazonCategory);
                $writedNewCategories=true;
            }
        }
        $this->em->flush();
        
            if($writedNewCategories){
                $io->success('New categories saved');
            }else{
                $io->note('No new categories');
            }
        }
        
    
}
