<?php
error_reporting(E_ALL & ~E_NOTICE);

class Server {

  protected $status;
  protected $statusMessage;
  protected $PID;

  function __construct($imagename="TerrariaServer.exe") {
    $this->setStatus($imagename);
  }

  public function start() {
    $this->statusMessage = "Server starting";
  }

  public function stop() {
    $this->statusMessage = "Server stopping";
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($imagename) {
    $PID = $this->PID($imagename);
    var_dump($PID); die();
    $this->status = ($PID !== false);
    $this->PID = $PID;
  }

  public function getPID() {
    return $this->PID;
  }

  public function PID($imagename) {
    ob_start();
    passthru('wmic process where (name="'.$imagename.'") get ProcessId');
    $wmic_output = ob_get_contents();
    ob_end_clean();

    // Remove everything but numbers and commas between numbers from output:
    $wmic_output = preg_replace(
      array('/[^0-9\n]*/','/[^0-9]+\n|\n$/','/\n/'),
      array('','',','),
      $wmic_output );

    if ($wmic_output != '') {

      // WMIC returned valid PId, should be safe to convert to int:
      $wmic_output = array_filter(explode(',', $wmic_output));

      foreach ($wmic_output as $k => $v) {
        $wmic_output[$k] = (int)$v;
      }

      return $wmic_output;
    }

    // WMIC did not return valid PId
    return false;
  }
}

$server = new Server;
var_dump($server);
var_dump($server->getStatus());
var_dump($server->getPID());