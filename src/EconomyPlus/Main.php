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
use EconomyPlus\API\EconomyPlusAPI;
use EconomyPlus\Tasks\Updater\UpdateCheckTask;

use pocketmine\utils\Utils;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class Main extends PluginBase implements Listener{

  static $api;

  public $lang = "";

  public $imported;

  public $shop = C::GRAY . "[" . C::GREEN . "Shop" . C::GRAY . "]";

  public $sell = C::GRAY . "[" . C::AQUA . "Sell" . C::GRAY . "]";

  public $perm = C::GRAY . "[" . C::RED . "Perm" . C::GRAY . "]";

  private $toplist;
  
  public function onLoad(){
    $this->saveAllLangs();
  }
  
  public function onEnable(){
    @mkdir($this->getDataFolder());
    if(!file_exists($this->getServer()->getDataPath() . "/plugins/EconomyPlus.phar")){
      $this->getLogger()->warning("Please insure that you have renamed EconomyPlus_vX.X.X to 'EconomyPlus.phar'");
      $this->getServer()->getPluginManager()->disablePlugin($this);
      return;
    }
    $this->saveDefaultConfig();
    static::$api = new EconomyPlusAPI($this);
    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->lang = $this->cfg->get("Default-Lang");
    $this->imported = $this->cfg->get("AccountsImported");
    $this->getLang();
    $this->langFile = new Config($this->getDataFolder() . "/languages/" . $this->lang . ".yml", Config::YAML);
    $this->getServer()->getScheduler()->scheduleAsyncTask($task = new UpdateCheckTask($this->cfg->get("Version"), "stable", $this->langFile->getAll(), true));
    $this->registerCommands();
    $this->registerListeners();
    $this->getServer()->getPluginManager()->registerEvents($this ,$this);
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getLogger()->info(C::YELLOW . "EconomyPlus v" . $this->cfg->get("Version") . " Enabled!");
    $this->importEconomyAPI();
  }

  public function saveAllLangs(){
    foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getFile() . "resources/languages")) as $resource){
      $resource = str_replace("\\", "/", $resource);
      $resarr = explode("/", $resource);
      if(substr($resarr[count($resarr) - 1], strrpos($resarr[count($resarr) - 1], '.') + 1) == "yml"){
        $this->saveResource("languages/" . $resarr[count($resarr) - 1]);
      }
    }
  }

  public function getLang(){
    $lang = strtolower($this->cfg->get("Default-Lang"));
    if(($lang == "eng") or ($lang == "english")){
      return $this->lang = "eng";
    }
    else if(($lang == "fre") or ($lang == "french")){
      return $this->lang = "fre";
    }
    else if(($lang == "por") or ($lang == "portuguese")){
      return $this->lang = "por";
    }
    else if(($lang == "ger") or ($lang == "german")){
      return $this->lang = "ger";
    }
    else if(($lang == "chi") or ($lang == "chinese")){
      return $this->lang = "chi";
    }
    else if(($lang == "schi") or ($lang == "simplified chinese")){
      return $this->lang = "schi";
    }
    else if(($lang == "rus") or ($lang == "russian")){
      return $this->lang = "rus";
    }
    else{
      $this->getLogger()->error(C::RED . "Invalid Language! Using English as Default Language!");
      return $this->lang = "eng";
    }
  }

  public function translate(String $msgType){
    $msg = $this->langFile->get($msgType);
    return $msg;
  }

  public function updateVersion($version){
    $this->cfg->set("Version", $version);
    $this->cfg->save();
    return true;
  }

  public function getPath(){
    return $this->getServer()->getDataPath() . "/plugins/EconomyPlus.phar";
  }

  public static function getInstance(){
    return static::$api;
  }

  public function allMoney(){
    $cfg = new Config($this->getDataFolder() . "/players.json", Config::JSON);
    return $cfg->getAll();
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

  public function format(String $message){
    return $message;
  }

  public function importEconomyAPI(){
    if($this->imported){
      return;
    }
    if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") == null){
      return;
    }
    $mny = \onebone\economyapi\EconomyAPI::getInstance()->getAllMoney();
    $money = $mny["money"];
    $count = 0;
    $this->getLogger()->info("Importing EconomyAPI money data...");
    foreach($money as $p => $m){
      $count++;
      $cfg = new Config($this->getDataFolder() . "/players.json", Config::JSON);
      if($cfg->exists($p)){
        $this->getLogger()->warning("Account " . $p . " exists! Overwriting...");
      }
      $cfg->set($p, $m);
      $cfg->save();
    }
    $this->cfg->set("AccountsImported", true);
    $this->cfg->save();
    $this->getLogger()->info("Sucessfully imported " . $count . " accounts!");
  }
}
