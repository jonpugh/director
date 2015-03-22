<?php

namespace DevShop\Command;

use DevShop\DevShopApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
  public $app;

  function __construct(DevShopApplication $app) {
    parent::__construct();
    $this->app = $app;
  }

  protected function configure()
  {
    $this
      ->setName('status')
      ->setDescription('Display the current status.')
      ->addArgument(
        'server',
        InputArgument::OPTIONAL,
        'Which server?'
      )
    ;
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $name = $input->getArgument('server');
    if (!$name) {
      $name = 'localhost';
    }
    $output->writeln("Hello World!");
    $output->writeln("Server: " . $this->app->data['server']);

    $table = $this->getHelper('table');
    $table->setHeaders(array('Name', 'Description', 'Repo'));

    foreach ($this->app->data['apps'] as $app) {
      $app = (object) $app;
      $table->setRows(array(
        array(
          $app->name,
          $app->description,
          $app->source_url,
        )
      ));
    }
    $table->render($output);
  }
}