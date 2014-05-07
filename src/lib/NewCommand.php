<?php namespace WordPress\Craft;

use ZipArchive;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

class NewCommand extends BaseCommand {

	/**
	 * Configure the console command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('new')
			 ->setDescription('Creating a new WordPress Install')
			 ->addArgument('name', InputArgument::REQUIRED, 'The name of the application');
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$directory = getcwd().'/'.$input->getArgument('name');

		if (is_dir($directory))
		{
			$output->writeln('<error>It looks like that folder already exists.  Please use a new directory.</error>'); exit(1);
		}

		$output->writeln('<info>Creating WordPress...</info>');

		// Creaqte the ZIP file name...
		$zipFile = getcwd().'/wordpress_'.md5(time().uniqid()).'.zip';

		// Download the latest WordPress archive...
		$client = new HttpClient;
		$client->get('http://wordpress.org/latest.zip')->setResponseBody($zipFile)->send();

		// Create the application directory...
		mkdir($directory);

		// Unzip the WordPress archive into the directory...
		$archive = new ZipArchive;
		$archive->open($zipFile);
		$archive->extractTo($directory);
		$archive->close();

		// Delete the WordPress archive...
		@chmod($zipFile, 0777);
		@unlink($zipFile);

		self::recurse_copy($directory.'/wordpress', $directory);
		
		@unlink($directory.'/wordpress');
		

		$output->writeln('<comment>Your WordPress Install is Ready!</comment>');
	}


	static private function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                self::recurse_copy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 


}