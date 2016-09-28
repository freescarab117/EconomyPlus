<?php
namespace EconomyPlus\Tasks\Updater;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use pocketmine\utils\Utils;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PharPluginLoader;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class UpdateInstallTask extends AsyncTask{

  public $path, $file;

  public function __construct(String $path, String $file){
    $this->path = $path;
    $this->file = $file;
  }

  public function onRun(){
    $contents = Utils::getUrl("https://github.com/ImagicalGamer/EconomyPlus/raw/" . $this->channel . "/latest.phar.phar");
    unlink($this->file);
    file_put_contents($this->path . "/EconomyPlus.phar", $contents);
  }

  public function onCompletion(Server $server){
  	echo(TextFormat::toANSI(TextFormat::AQUA . "[" . date("H:i:s", time()) . "] " . TextFormat::RESET . TextFormat::WHITE . "[Server thread/INFO]: [EconomyPlus] " . TextFormat::AQUA . "EconomyPlus updated to the latest version, please restart your server to enable the latest version!" . "\n" . TextFormat::WHITE));
  }
}
