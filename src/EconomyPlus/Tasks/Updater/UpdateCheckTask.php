<?php
namespace EconomyPlus\Tasks\Updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;
use pocketmine\utils\TextFormat;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class UpdateCheckTask extends AsyncTask{

  protected $current_version, $new_version, $channel, $has_update, $update_version, $messages;

  public function __construct($version, String $channel, Array $messages){
    $this->current_version = $version;
    $this->channel = $channel;
    $this->messages = $messages;
    $this->has_update = null;
  }

  public function onRun(){
    $this->new_version = Utils::getUrl("https://github.com/ImagicalGamer/EconomyPlus/raw/" . $this->channel . "/version.txt");
    if($this->new_version > $this->current_version){
      $this->has_update = true;
      return;
    }
    $this->has_update = false;
  }

  public function onCompletion(Server $server){
    if($this->has_update === true){
      echo(TextFormat::toANSI(TextFormat::AQUA . "[" . date("H:i:s", time()) . "] " . TextFormat::RESET . TextFormat::WHITE . "[Server thread/INFO]: [EconomyPlus] " . TextFormat::GREEN . $this->messages["UPDATE-FOUND"] . "\n" . TextFormat::WHITE));
      echo(TextFormat::toANSI(TextFormat::AQUA . "[" . date("H:i:s", time()) . "] " . TextFormat::RESET . TextFormat::WHITE . "[Server thread/INFO]: [EconomyPlus] " . TextFormat::GREEN . "Preparing to Install latest updates...\n" . TextFormat::WHITE));
      $path = $server->getPluginManager()->getPlugin("EconomyPlus")->getPath();
      $server->getScheduler()->scheduleAsyncTask($task = new UpdateInstallTask($server->getDataPath() . "/plugins/", $path));
      $server->getPluginManager()->getPlugin("EconomyPlus")->updateVersion($this->new_version);
      return;
    }
    echo(TextFormat::toANSI(TextFormat::AQUA . "[" . date("H:i:s", time()) . "] " . TextFormat::RESET . TextFormat::WHITE . "[Server thread/INFO]: [EconomyPlus] " . TextFormat::AQUA . $this->messages["NO-UPDATE"] . "\n" . TextFormat::WHITE));
  }
}
