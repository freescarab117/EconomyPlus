<?php
namespace EconomyPlus\Language;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;

use pocketmine\utils\Config;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class Language extends PluginBase{

  public function __construct(Main $plugin, String $message, String $lang = "eng"){
    $this->plugin = $plugin;
    $this->message = $message;
    $this->lang = $lang;
  }

  public function translate(){
    return $this->message;
  }
}
