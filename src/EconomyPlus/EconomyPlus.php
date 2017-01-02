<?php
namespace EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\utils\Utils;

use EconomyPlus\Commands\BalanceCommand;
use EconomyPlus\Commands\AddMoneyCommand;
use EconomyPlus\Commands\TakeMoneyCommand;
use EconomyPlus\Commands\PayMoneyCommand;
use EconomyPlus\Commands\TopMoneyCommand;

use EconomyPlus\EventListener;

use EconomyPlus\Shop\ShopListener;
use EconomyPlus\Shop\SellListener;
use EconomyPlus\Shop\PermListener;

use EconomyPlus\API\EconomyPlusAPI;

use EconomyPlus\Provider\JsonProvider;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, December 2016
 */

class EconomyPlus extends PluginBase implements Listener{

  protected $api;

  public $mysql_settings = ["host" => '127.0.0.1', "port" => 3303, "user" => null, "password" => null, "db_name" => null];

  public $lang = "";

  protected $provider;

  public $shop = TextFormat::GRAY . "[" . TextFormat::GREEN . "Shop" . TextFormat::GRAY . "]";

  public $sell = TextFormat::GRAY . "[" . TextFormat::AQUA . "Sell" . TextFormat::GRAY . "]";
  
  public function onLoad()
  {
    foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->getFile() . "resources/languages")) as $resource){
      $resource = str_replace("\\", "/", $resource);
      $resarr = explode("/", $resource);
      if(substr($resarr[count($resarr) - 1], strrpos($resarr[count($resarr) - 1], '.') + 1) == "yml"){
        $this->saveResource("languages/" . $resarr[count($resarr) - 1]);
      }
    }
  }
  
  public function onEnable()
  {
    @mkdir($this->getDataFolder());
    $this->saveDefaultConfig();

    $this->cfg = new Config($this->getDataFolder() . "/config.yml", Config::YAML);
    $this->cfg->save();
    $this->lang = $this->cfg->get("Default-Lang");
    $this->getLang();
    $this->langFile = new Config($this->getDataFolder() . "/languages/" . $this->lang . ".yml", Config::YAML);

    //if(strtolower($this->cfg->get("provider")) == "json")
    //{
      $this->provider = new JsonProvider($this);
    //}
    /*elseif(strtolower($this->cfg->get("provider")) == "mysql")
    {
      $this->mysql_settings = $this->cfg->get("mysql");
      $this->provider = new MySQLProvider($this, $this->mysql_settings);
    */}

    $this->api = new EconomyPlusAPI($this, $this->provider);

    $this->shop = str_replace("@", "§", $this->cfg->get("ShopPrefix"));
    $this->sell = str_replace("@", "§", $this->cfg->get("SellPrefix"));
    $this->perm = str_replace("@", "§", $this->cfg->get("PermPrefix"));

    $this->getServer()->getCommandMap()->register("bal", new BalanceCommand($this));
    $this->getServer()->getCommandMap()->register("addmoney", new AddMoneyCommand($this));
    $this->getServer()->getCommandMap()->register("takemoney", new TakeMoneyCommand($this));
    $this->getServer()->getCommandMap()->register("pay", new PayMoneyCommand($this));
    $this->getServer()->getCommandMap()->register("topmoney", new TopMoneyCommand($this));

    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getServer()->getPluginManager()->registerEvents(new ShopListener($this, $this->shop), $this);
    $this->getServer()->getPluginManager()->registerEvents(new SellListener($this, $this->sell), $this);
    $this->getLogger()->info(TextFormat::YELLOW . "EconomyPlus v" . $this->getDescription()->getVersion() . " Enabled!");
  }

  public function getLang()
  {
    $lang = strtolower($this->cfg->get("Default-Lang"));
    if(($lang == "eng") or ($lang == "english")){
      return $this->lang = "eng";
    }
    elseif(($lang == "fre") or ($lang == "french")){
      return $this->lang = "fre";
    }
    elseif(($lang == "por") or ($lang == "portuguese")){
      return $this->lang = "por";
    }
    elseif(($lang == "ger") or ($lang == "german")){
      return $this->lang = "ger";
    }
    elseif(($lang == "chi") or ($lang == "chinese")){
      return $this->lang = "chi";
    }
    elseif(($lang == "schi") or ($lang == "simplified chinese")){
      return $this->lang = "schi";
    }
    elseif(($lang == "rus") or ($lang == "russian")){
      return $this->lang = "rus";
    }
    else{
      $this->getLogger()->error(TextFormat::RED . "Invalid Language! Using English as Default Language!");
      return $this->lang = "eng";
    }
  }

  public function translate(String $msgType)
  {
    $msg = $this->langFile->get($msgType);
    return (string) $msg;
  }

  public static function getInstance()
  {
    return $this->api;
  }

  public static function getProvider()
  {
    return $this->provider;
  }

  public function registerListeners()
  {
    if(boolval($this->cfg->get("EnableShop")) === true){
      $this->getServer()->getPluginManager()->registerEvents(new ShopListener($this, $this->shop), $this);
    }
    if(boolval($this->cfg->get("EnableSell")) === true){
      $this->getServer()->getPluginManager()->registerEvents(new SellListener($this, $this->sell), $this);
    }
    return true;
  }

  public static function sell(Player $player, String $item, Int $ammount, Int $price)
  {
    $itm = Item::fromString($item);
    $itm->setCount($amount);
    if($player->getInventory()->contains($itm)){
      $this->provider->setMoney(strtolower($player), $this->provider->getMoney($player) + $price);
      if($player->getInventory()->contains($itm)){
        $removed = 0;
        for($i= 0; $i < 36; $i++){
          $item = $player->getInventory()->getItem($i);
            if($item->getId() == $itm->getId()){
                if($item->getCount() >= $amount){
                  $item->setCount($item->getCount() - $amount);
                  $player->getInventory()->setItem($i, $item);
                  $player->getInventory()->sendContents($player);
                  break;
              }    
              else
              {
              if($item->getCount() + $removed >= $amount){
                $item->setCount($item->getCount() - ($amount - $removed));
                $player->getInventory()->setItem($i, $item);
                $player->getInventory()->sendContents($player);
                break;
             }
             else{
                $removed += $item->getCount();
                $item->setCount(0);
                $player->getInventory()->setItem($i, $item);
             }
            }
          }
        }
    }
    $player->sendMessage(TextFormat::GREEN . "You have sold " . TextFormat::YELLOW . $amount . TextFormat::GREEN . " of " . TextFormat::YELLOW . $itm->getName() . TextFormat::GREEN . " for $" . TextFormat::YELLOW . $price);
      return true;
    }
    else{
      if($itm instanceof ItemBlock){
        $player->sendMessage(TextFormat::GREEN . "You do not have " . TextFormat::YELLOW . $itm->getBlock()->getName());
        return false;
      }
      $player->sendMessage(TextFormat::GREEN . "You do not have " . TextFormat::YELLOW . $itm->getAmount() . TextFormat::GREEN . " of " . TextFormat::YELLOW . $item->getName());
    }
  }

  public static function buy(Player $player, String $item, int $amount, int $price){
    if($this->provider->getMoney($player) <= $amount)
    {
      return false;
    }
    $itm = Item::fromString($item);
    $itm->setCount($amount);
    $player->getInventory()->addItem($itm);
    $this->provider->setMoney($player, $this->provider->getMoney($player) - $price);
    $player->sendMessage(TextFormat::GREEN . "You have bought " . TextFormat::YELLOW . $amount . TextFormat::GREEN . " of " . TextFormat::YELLOW . $itm->getName() . TextFormat::GREEN . " for $" . TextFormat::YELLOW . $price);
    return true;
  }
}
