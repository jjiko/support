<?php error_reporting(E_ALL & ~E_NOTICE);
class Server {

  protected $status;
  protected $statusMessage;
  protected $PID;

  function __construct($imagename="TerrariaServer.exe") {
    $this->setStatus($imagename);

    switch(strtolower($imagename)) {
      case "ts3server_win64.exe":
        $this->servername = "ts3server";
        break;

      case "terrariaserver.exe":
        $this->servername = "terraria";
        break;
      case "starbound_server.exe":
        $this->servername = "starbound";
        break;

    }
  }

  public function start($servername="terraria") {
    $this->statusMessage = "Server starting";
    switch($this->servername) {
      case "starbound":
        self::start_starbound();
        break;
      case "terraria":
        self::start_terraria();
        break;
    }

  }

  public static function start_terraria()
  {
    shell_exec('SCHTASKS /F /Create /TN _ters1 /TR "\"C:\Program Files (x86)\Steam\SteamApps\common\Terraria\TerrariaServer.exe\" -config \"C:\Program Files (x86)\Steam\SteamApps\common\Terraria\serverconfig.txt\"" /SC DAILY /RU INTERACTIVE');
    shell_exec('SCHTASKS /RUN /TN "_ters1"');
    shell_exec('SCHTASKS /DELETE /TN "_ters1" /F');
  }

  public function start_starbound()
  {
    shell_exec('SCHTASKS /F /Create /TN _sbs1 /TR %windir%\system32\cmd.exe /K cd "C:\Program Files (x86)\Steam\SteamApps\common\Starbound\win64" & starbound_server.exe /SC DAILY /RU INTERACTIVE');
    shell_exec('SCHTASKS /RUN /TN "_sbs1"');
    shell_exec('SCHTASKS /DELETE /TN "_sbs1" /F');
  }

  public function stop() {
    foreach($this->PID as $PID) {
      ob_start();
      passthru("wmic process $PID delete");
      echo ob_get_contents();
      ob_end_clean();
    }

    return true;
  }

  public function getStatus() {
    return $this->status;
  }

  public function setStatus($imagename) {
    $PID = $this->PID($imagename);
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