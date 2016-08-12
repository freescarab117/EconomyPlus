<?php
namespace EconomyPlus;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;
use pocketmine\item\ItemBlock;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class EconomyPlayer extends PluginBase{

  public function __construct(Main $plugin, String $player, $hasFile = null){
    $this->plugin = $plugin;
    $this->player = $player;
    $this->hasFile = $hasFile;
    $this->cfg = new Config($this->plugin->getDataFolder() . "/players.json", Config::JSON);
  }

  public function getMoney(){
    return intval($this->cfg->get(strtolower($this->player)));
  }

  public function subtractMoney(int $ammount){
    $money = $this->getMoney();
    if($money > intval($ammount)){
      $this->setMoney($money - $ammount);
      return true;
    }
    return false;
  }

  public function setMoney(int $ammount){
    $this->cfg->set(strtolower($this->player), round($ammount));
    $this->cfg->save();
    return true;
  }

  public function addMoney(int $ammount){
    $this->setMoney($ammount + $this->getMoney());
    return true;
  }

  public function newPlayer(){
    $this->cfg->set(strtolower($this->player), $this->plugin->cfg->get("Default-Money"));
    $this->cfg->save();
    return true;
  }

  public function sendMessage(String $message){
    $this->plugin->getServer()->getPlayer($this->player)->sendMessage($message);
  }

  public function pay(int $ammount, String $payer){
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
    $itm = Item::get(intval($item), 0, intval($ammount));
    $this->plugin->getServer()->getPlayer($this->player)->getInventory()->addItem($itm);
    $this->subtractMoney($price);
    $this->sendMessage($this->plugin->translate(C::GREEN . "You have bought " . C::YELLOW . $ammount . C::GREEN . " of " . C::YELLOW . $itm->getName() . C::GREEN . " for $" . C::YELLOW . $price));
    return true;
  }

  public function sell(String $item, int $ammount, int $price){
    $itm = Item::get(intval($item), 0, intval($ammount));
    if($this->plugin->getServer()->getPlayer($this->player)->getInventory()->contains($itm)){
      $this->addMoney($price);
      $this->sendMessage($this->plugin->translate(C::GREEN . "You have sold " . C::YELLOW . $ammount . C::GREEN . " of " . C::YELLOW . $itm->getName() . C::GREEN . " for $" . C::YELLOW . $price));
      $this->plugin->getServer()->getPlayer($this->player)->getInventory()->remove($itm);
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
}