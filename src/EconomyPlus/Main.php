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
use EconomyPlus\Commands\TopMoneyCommand;
use EconomyPlus\EventListener;
use EconomyPlus\Shop\ShopListener;
use EconomyPlus\Shop\SellListener;
use EconomyPlus\Shop\PermListener;
use EconomyPlus\Language\Language;
use EconomyPlus\Tasks\TopMoneyTask;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class Main extends PluginBase implements Listener{

  protected $api;

  protected $lang = array("eng");

  public $shop = C::GRAY . "[" . C::GREEN . "Shop" . C::GRAY . "]";

  public $sell = C::GRAY . "[" . C::AQUA . "Sell" . C::GRAY . "]";

  public $perm = C::GRAY . "[" . C::RED . "Perm" . C::GRAY . "]";

  private $toplist;
  
  public function onLoad(){
    ini_set("extension", "extension=php_openssl.dll");
  }
  
  public function onEnable(){
    ini_set("extension", "extension=php_openssl.dll");
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->hasUpdates();
    $this->registerCommands();
    $this->registerListeners();
    $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getLogger()->info(C::YELLOW . "EconomyPlus v" . $this->cfg->get("Version") . " Enabled!");
  }

  public function translate(String $message, String $lang = "eng", String $type = "%UNKNOWN%"){
    if(in_array($lang, $this->lang)){
      $translator = new Language($this, $message, $lang);
      return $translator->translate();
    }
    else{
      return null;
    }
  }

  public function getInstance(){
    return $this;
  }

  public function allMoney(){
    $cfg = new Config($this->getDataFolder() . "/players.json", Config::JSON);
    return $cfg->getAll();
  }

  public function getLang(){
    return $cfg->get("Default-Lang");
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
    if($this->isCommandEnabled("topmoney") == true){
      $this->getServer()->getCommandMap()->register("topmoney", new TopMoneyCommand($this));
    }
  }

  public function registerListeners(){
    if($this->cfg->get("EnableShop") === true){
      $this->getServer()->getPluginManager()->registerEvents(new ShopListener($this, $this->shop), $this);
    }
    if($this->cfg->get("EnableSell") === true){
      $this->getServer()->getPluginManager()->registerEvents(new SellListener($this, $this->sell), $this);
    }
    if($this->cfg->get("EnablePermShop") === true){
      $this->getServer()->getPluginManager()->registerEvents(new PermListener($this, $this->perm), $this);
    }
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
  
  public function myMoney(Player $p){
    $p1 = new EconomyPlayer($this, $p->getName());
    return($p1->getMoney());
  }

  public function hasUpdates(){
    $version = $this->cfg->get("Version");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://raw.githubusercontent.com/ImagicalGamer/EconomyPlus/master/resources/version");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $nversion = curl_exec($ch);
    curl_close($ch);
    if(!$nversion > $version){
      $this->getLogger()->info(C::YELLOW . "An EconomyPlus Update Has Been Found!");
      return true;
    }
    $this->getLogger()->info(C::AQUA . "No Updates Found! Your using the latest version of EconomyPlus!");
    return false;
  }

  public function format(String $message){
    return $message;
  }
}
