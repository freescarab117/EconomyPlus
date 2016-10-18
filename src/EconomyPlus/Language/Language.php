<?php
namespace EconomyPlus\Language;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\EconomyPlus;

use pocketmine\utils\Config;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class Language extends PluginBase{

  public function __construct(EconomyPlus $plugin, String $message, String $lang = "eng", String $type = null){
    $this->plugin = $plugin;
    $this->message = $message;
    $this->lang = $lang;
    $this->type = $type;
    $this->langFile = new Config($this->getDataFolder() . "/Language/" . $this->lang . ".yml", Config::YAML);
  }

  public function translate(){
    return $this->message;
  }

  public function getMessageType(){
  	return $this->type;
  }

  public function getLang(){
  	return $this->lang;
  }
}