<?php
namespace Director\Service;
use Director\DirectorApplication;
use GitWrapper\GitWrapper;
/**
 * Service for an App.
 */
class AppService {

  public $name;
  public $app;
  public $director;

  public function __construct($name, $data, DirectorApplication $director) {
    $this->name = $name;
    $this->director = $director;
    $this->app = (object) $data;

    $this->description = $this->app->description;
    $this->source_url = $this->app->source_url;

  }

  /**
   * Clones the source code for this project.
   */
  public function init($path){

    $wrapper = new GitWrapper();
    $wrapper->streamOutput();
    $working_copy = $wrapper->clone($this->app->source_url, $path);

    chdir($path);
    $wrapper->git('status');
  }
}