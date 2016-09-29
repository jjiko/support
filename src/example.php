<?php

$terrariaServer = new Server;
$starboundServer = new Server('starbound_server.exe');
$ts3Server = new Server;

if(isset($_REQUEST['command'])) {
  switch(strtolower($_REQUEST['server'])) {
    case "terraria":
      switch(strtolower($_REQUEST['command'])) {
        case "start":
          if(!$terrariaServer->getStatus()) {
            $terrariaServer->start();
          }
          break;
        case "stop":
          if($terrariaServer->getStatus()) {
            $terrariaServer->stop();
          }
          break;
      }
      break;

    case "starbound":
      switch(strtolower($_REQUEST['command'])) {
        case "start":
          if(!$starboundServer->getStatus()) {
            $starboundServer->start();
          }
          break;
        case "stop":
          if($starboundServer->getStatus()) {
            $starboundServer->stop();
          }
          break;
      }
      break;
  }

}
?>
<!doctype html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" />
<div class="container">
  <div class="row">
    <?php $socket = fsockopen("sbs1.errantnights.com", 8000); ?>

  </div>
  <div class="row">
    <div class="col-md-12">
      <h1>
        Terraria
      </h1>
      <h2>ters1.errantnights.com:7777 <?php if($terrariaServer->getStatus()): ?>
          <span class="bg-success">Online</span> <?php echo $terrariaServer->getPID()[1]; ?>
        <?php else: ?>
          <span class="bg-danger">Offline</span>
        <?php endif; ?></h2>
      <form>
        <input type="hidden" name="server" value="terraria">
        <button class="btn btn-default btn-success" type="submit" value="start" name="command">Start</button>
        <button class="btn btn-default btn-danger" type="submit" value="stop" name="command">Stop</button>
        <button class="btn btn-default" onclick="javascript:window.refresh">Refresh</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h1>
        Starbound
      </h1>
      <h2>sbs1.errantnights.com <?php if($starboundServer->getStatus()): ?>
          <span class="bg-success">Online</span> <?php echo $starboundServer->getPID()[1]; ?>
        <?php else: ?>
          <span class="bg-danger">Offline</span>
        <?php endif; ?></h2>
      <form>
        <input type="hidden" name="server" value="starbound">
        <button class="btn btn-default btn-success" type="submit" value="start" name="command">Start</button>
        <button class="btn btn-default btn-danger" type="submit" value="stop" name="command">Stop</button>
        <button class="btn btn-default" onclick="javascript:window.refresh">Refresh</button>
      </form>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <h1>
        Teamspeak
      </h1>
      <h2>voice.errantnights.com <?php if($ts3Server->getStatus()): ?>
          <span class="bg-success">Online</span> <?php echo $ts3Server->getPID()[1]; ?>
        <?php else: ?>
          <span class="bg-danger">Offline</span>
        <?php endif; ?></h2>
      <form>
        <input type="hidden" name="server" value="starbound">
        <button class="btn btn-default btn-success" type="submit" value="start" name="command">Start</button>
        <button class="btn btn-default btn-danger" type="submit" value="stop" name="command">Stop</button>
        <button class="btn btn-default" onclick="javascript:window.refresh">Refresh</button>
      </form>
    </div>
  </div>
</div>