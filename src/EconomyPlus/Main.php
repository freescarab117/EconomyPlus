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
use EconomyPlus\Shop\ShopListener;
use EconomyPlus\Language\Language;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class Main extends PluginBase implements Listener{

  protected $api;

  protected $lang = array("eng");

  public $shop = C::GRAY . "[" . C::GREEN . "Shop" . C::GRAY . "]";

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    ini_set("extension", "extension=php_openssl.dll");
    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->hasUpdates();
    $this->registerCommands();
    $this->registerListeners();
    $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    $this->getLogger()->info(C::GREEN . "Enabled!");
  }

  public function translate(String $message, String $lang = "eng", String $type = "msg"){
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

  public function registerListeners(){
    if($this->cfg->get("EnableShop") === true){
      $this->getServer()->getPluginManager()->registerEvents(new ShopListener($this, $this->shop), $this);
    }
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    return true;
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
    if(!$nversion > $version){
      $this->getLogger()->info(C::YELLOW . "An EconomyPlus Update Has Been Found!");
      return true;
    }
    $this->getLogger()->info(C::AQUA . "No Updates Found! Your using the latest version of EconomyPlus!");
    return false;
  }
}