<?php
namespace EconomyPlus;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;
use EconomyPlus\Events\Player\PlayerMoneyChangeEvent;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class EconomyPlayer extends PluginBase{
  
  const UNKNOWN = 0;
  const REDUCE = 1;
  const ADD = 2;
  const SET = 3;
  const PAY = 4;

  private $player;
  private $plugin;
  
  public function __construct(Plugin $plugin, String $player){
    $this->plugin = $plugin;
    $this->player = $player;
    $this->cfg = new Config($this->plugin->getDataFolder() . "/players.json", Config::JSON);
    if($this->cfg->exists(strtolower($player)) == null)
    {
      $this->cfg->set(strtolower($this->player), $this->plugin->cfg->get("Default-Money"));
      $this->cfg->save();
      $this->cfg->reload();
    }
    $this->cfg->save();
  }

  public function getMoney(){
    $this->cfg->reload();
    return intval($this->cfg->get(strtolower($this->player)));
  }

  public function subtractMoney(int $ammount){
    $this->plugin->getServer()->getPluginManager()->callEvent($ev = new PlayerMoneyChangeEvent($this->plugin, $this, $ammount, self::REDUCE));
    if($ev->isCancelled()){
      return;
    }
    $money = $this->getMoney();
    if($money > intval($ammount)){
      $this->setMoney($money - $ammount);
      return true;
    }
    return false;
  }

  public function setMoney(int $ammount){
    $this->plugin->getServer()->getPluginManager()->callEvent($ev = new PlayerMoneyChangeEvent($this->plugin, $this, $ammount, self::SET));
    if($ev->isCancelled()){
      return;
    }
    $this->cfg->reload();
    $this->cfg->set(strtolower($this->player), round($ammount));
    $this->cfg->save();
    $this->cfg->reload();
    return true;
  }

  public function addMoney(int $ammount){
    $this->plugin->getServer()->getPluginManager()->callEvent($ev = new PlayerMoneyChangeEvent($this->plugin, $this, $ammount, self::ADD));
    if($ev->isCancelled()){
      return;
    }
    $this->setMoney($ammount + $this->getMoney());
    return true;
  }

  public function sendMessage(String $message){
    $this->plugin->getServer()->getPlayer($this->player)->sendMessage($message);
  }

  public function pay(int $ammount, String $payer){
    $this->plugin->getServer()->getPluginManager()->callEvent($ev = new PlayerMoneyChangeEvent($this->plugin, $this, $ammount, self::ADD));
    if($ev->isCancelled()){
      return;
    }
    $this->addMoney($ammount);
    $this->plugin->getServer()->getPlayer($this->player)->sendMessage(C::YELLOW . $payer . C::GREEN . " has payed you $" . C::YELLOW . $ammount);
  }

  public function canBuy(int $price){
    if($this->getMoney() >= $price){
      return true;
    }
    return false;
  }
  public function buy(String $item, int $ammount, int $price){
    if($this->canBuy($price) == false){
      return false;
    }
    $itm = Item::get(intval($item), 0, intval($ammount));
    $this->plugin->getServer()->getPlayer($this->player)->getInventory()->addItem($itm);
    $this->cfg->set(strtolower($this->player), $this->getMoney() - $price);
    $this->cfg->save();
    $this->cfg->reload();
    $this->sendMessage(C::GREEN . "You have bought " . C::YELLOW . $ammount . C::GREEN . " of " . C::YELLOW . $itm->getName() . C::GREEN . " for $" . C::YELLOW . $price);
    return true;
  }

  public function sell(String $item, int $ammount, int $price){
    $itm = Item::get(intval($item), 0, intval($ammount));
    if($this->plugin->getServer()->getPlayer($this->player)->getInventory()->contains($itm)){
      $this->cfg->set(strtolower($this->player), $this->getMoney() + $price);
      $this->cfg->save();
      $this->cfg->reload();
      $player = $this->plugin->getServer()->getPlayer($this->player);
      if($player->getInventory()->contains($itm)){
      $removed = 0;
      for($i=0;$i<36;$i++){
         $item = $player->getInventory()->getItem($i);
           if($item->getId() == $itm->getId()){
              if($item->getCount() >= 10){
                $item->setCount($item->getCount() - 10);
                 $player->getInventory()->setItem($i, $item);
                 $player->getInventory()->sendContents($player);
                 break;
            }    else{
             if($item->getCount() + $removed >= 10){
                $item->setCount($item->getCount() - (10 - $removed));
                $player->getInventory()->setItem($i, $item);
                $player->getInventory()->sendContents($player);
                break;
             }else{
                $removed += $item->getCount();
                $item->setCount(0);
                $player->getInventory()->setItem($i, $item);
             }
          }
      }
   }
}
      $this->sendMessage(C::GREEN . "You have sold " . C::YELLOW . $ammount . C::GREEN . " of " . C::YELLOW . $itm->getName() . C::GREEN . " for $" . C::YELLOW . $price);
      return true;
    }
    else{
      if($itm instanceof ItemBlock){
        $this->plugin->getServer()->getPlayer($this->player)->sendMessage(C::GREEN . "You do not have " . C::YELLOW . $itm->getBlock()->getName());
        return false;
      }
      $this->plugin->getServer()->getPlayer($this->player)->sendMessage(C::GREEN . "You do not have " . C::YELLOW . $itm->getAmount() . C::GREEN . " of " . C::YELLOW . $item->getName());
    }
  }

  public function buyPerm(String $perm, Int $price){
    if($this->canBuy($price) == true){
      $this->subtractMoney($price);
      $p = $this->plugin->getServer()->getPlayer($this->player);
      $at = $p->addAttachment($this->plugin);
      $at->setPermission($perm, true);
      $p->sendMessage(C::GREEN . "You have bought " . C::YELLOW . $perm . C::GREEN . " for $" . C::YELLOW . $price);
      return true;
    }
    else{
      $this->plugin->getServer()->getPlayer($this->player)->sendMessage(C::RED . $this->plugin->translate("INVALID-BALANCE"));
      return false;
    } 
  }
}
