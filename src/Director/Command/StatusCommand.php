<?php

namespace Director\Command;

use Director\DirectorApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
  public $director;

  function __construct(DirectorApplication $director) {
    parent::__construct();
    $this->director = $director;
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
    $output->writeln("Server: " . $this->director->config['server']);

    // SERVERS table.
    $table = $this->getHelper('table');
    $table->setHeaders(array('SERVERS', 'Provider', 'IP'));

    $rows = array();
    foreach ($this->director->config['servers'] as $name => $server) {
      $server = (object) $server;
      $ips = !empty($server->ip_addresses)?
        implode(', ', $server->ip_addresses):
        '';
      $row = array(
        $server->hostname,
        $server->provider,
        $ips,
      );
      $rows[] = $row;
    }
    $table->setRows($rows);
    $table->render($output);


    // APPS table.
    $table = $this->getHelper('table');
    $table->setHeaders(array('APPS', 'Description', 'Repo', 'environments'));

    $rows = array();
    foreach ($this->director->apps as $app) {
      $environments_list = !empty($app->environments)?
        implode(', ', array_keys($app->environments)):
        '';
      $row = array(
        $app->name,
        $app->description,
        $app->source_url,
        $environments_list,
      );
      $rows[] = $row;
    }
    $table->setRows($rows);
    $table->render($output);


  }
}