<?php
namespace ImagicalGamer\EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, May 2016
 */

class Translator extends PluginBase implements Listener{

  public function translate($msg, $to_lang){
    if(in_array($to_lang, $this->langs)){
      $langs = new Config($this->getDataFolder() . "/lang.yml", Config::YAML);
    }
  }

  public function langs(){
    $langs = array("english","$french","german","dutch","chinese");
    return $langs;
  }

  public function getLang(){
    $langs = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $lang = $langs->get("Default-Lang");
    return $lang;
  }
}
