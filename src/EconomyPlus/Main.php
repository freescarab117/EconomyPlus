<?php
namespace EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;

use EconomyPlus\Commands\BalanceCommand;
use EconomyPlus\EventListener;
use EconomyPlus\Language\Language;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class Main extends PluginBase implements Listener{

  protected $api;

  protected $lang = array("eng");

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->getServer()->getCommandMap()->register("bal", new BalanceCommand($this));
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getLogger()->info(C::GREEN . "Enabled!");
  }

  public function translate(String $message, String $lang = "eng"){
    if(in_array($lang, $this->lang)){
      $translator = new Language($this, $message, $lang);
      return $translator->translate();
    }
    else{
      return null;
    }
  }
}
