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
use EconomyPlus\Commands\AddMoneyCommand;
use EconomyPlus\Commands\TakeMoneyCommand;
use EconomyPlus\Commands\PayMoneyCommand;
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
    ini_set("extension", "extension=php_openssl.dll");
    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->hasUpdates();
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    $this->registerCommands();
    $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getLogger()->info(C::GREEN . "Enabled!");
  }

  public function translate(String $message, String $lang = "eng", String $type){
    if(in_array($lang, $this->lang)){
      $translator = new Language($this, $message, $lang);
      return $translator->translate();
    }
    else{
      return null;
    }
  }

  public function registerCommands(){
    if($this->isCommandEnabled("bal") == true){
      $this->getServer()->getCommandMap()->register("bal", new BalanceCommand($this));
    }
    if($this->isCommandEnabled("addmoney") == true){
      $this->getServer()->getCommandMap()->register("addmoney", new AddMoneyCommand($this));
    }
    if($this->isCommandEnabled("takemoney") == true){
      $this->getServer()->getCommandMap()->register("takemoney", new TakeMoneyCommand($this));
    }
    if($this->isCommandEnabled("pay") == true){
      $this->getServer()->getCommandMap()->register("pay", new PayMoneyCommand($this));
    }
  }

  public function isCommandEnabled(String $cmd){
    if($this->cfg->get($cmd . "-Command") == true){
      return true;
    }
    else{
      return false;
    }
  }

  public function hasUpdates(){

    $version = $this->cfg->get("Version");
    $nversion = file_get_contents("https://raw.githubusercontent.com/ImagicalGamer/EconomyPlus/master/resources/version");
    if($nversion > $version){
      $this->getLogger()->info(C::YELLOW . "An EconomyPlus Update Has Been Found! Run the /update Command to update EconomyPlus!");
      return true;
    }
    return false;
  }
}