<?php namespace WordPress\Craft;

use ZipArchive;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command as BaseCommand;

class KeyCommand extends BaseCommand {

	/**
	 * Configure the console command.
	 *
	 * @return void
	 */
	protected function configure()
	{
		$this->setName('keys')
			 ->setDescription('Reset the Authentication Unique Keys and Salts');
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$file = getcwd().'/wp-config.php';

		if (!is_file($file))
		{
			$output->writeln('<error>We could not find your wp-config.php file.  Make sure you install wordpress first from the web broswer.</error>'); exit(1);
		}

		$output->writeln('<info>Generating New Keys...</info>');

		self::replace('AUTH_KEY');
    self::replace('SECURE_AUTH_KEY');
    self::replace('LOGGED_IN_KEY');
    self::replace('NONCE_KEY');
    self::replace('AUTH_SALT');
    self::replace('SECURE_AUTH_SALT');
    self::replace('LOGGED_IN_SALT');
    self::replace('NONCE_SALT');
		

		$output->writeln('<comment>Your Keys have been Updated!</comment>');
	}

  private function replace($item)
{
  $file = file_get_contents('wp-config.php');
  $search = "/(define\('$item',).*'/";
    $key = self::generateKey();
    
    $file = preg_replace($search, "define('$item', '$key'", $file);

      file_put_contents('wp-config.php', $file);

}

  static private function generateKey()
  {
    $length = 64; // 16 Chars long
    $key = "";
    for ($i=1;$i<=$length;$i++) {
      // Alphabetical range
      $alph_from = 65;
      $alph_to = 90;

      // Numeric
      $num_from = 48;
      $num_to = 57;

      // Special
      $spec_char = array(33,35,36,37,38,40,41,42,43,44,45,46,47);

      // Add characters
      switch(rand(0,2)) {
        case 0:
          $key.= strtolower(chr(rand($alph_from,$alph_to)));
          break;
        case 1:
          $key .= chr(rand($num_from,$num_to));
          break;
        case 2:
          $key .= chr($spec_char[rand(0,count($spec_char)-1)]);
          break;
      }
    }

    return $key;

  }




}