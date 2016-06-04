<?php
namespace ImagicalGamer\EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\command\{Command, CommandSender};
use pocketmine\command\ConsoleCommandSender;

use pocketmine\item\Item;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, May 2016
 */

class Main extends PluginBase implements Listener{

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    $this->saveResource("/items.yml");
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->save();
    $this->getLogger()->info(C::GREEN . "Money Data Found!");
  }

  public function addMoney($player, $bal){
    $balence = $this->myMoney($player);
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->set(strtolower($player),$balence + $bal);
    $money->save();
  }

  public function myMoney($player){
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $amount = $money->get(strtolower($player));
    return $amount;
  }

  public function checkPrice($item){
    $items = new Config($this->getDataFolder() . "/items.yml", Config::YAML);
    $price = $items->get(strtolower($item));
    return $price;
  }

  public function subtractMoney($player, $bal){
    $ammount = $this->myMoney($player);
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->set(strtolower($player),$ammount - $bal);
    $money->save();
  }

  public function payPlayer($player, $bal, $sender){
    if($this->myMoney($sender) >= $bal){
      $this->addMoney($player, $bal);
      $this->subtractMoney($sender, $bal);
    }
  }

  public function onJoin(PlayerJoinEvent $event){
    $player = $event->getPlayer();
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $def = $money->get("Default-Money");
    if($money->get($player->getName()) == null){
      $money->set(strtolower($player->getName()),$def);
      $money->save();
    }
    $money->save();
  }

  public function onDeath(PlayerDeathEvent $event){
    $entity = $event->getEntity();
    $cause = $entity->getLastDamageCause();
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $def = $money->get("Death-Money");
    if($cause instanceof Player){
      $this->addMoney($cause->getName,$def);
      $cause->sendMessage(C::GREEN . "You earned " . $def . " Coins!");
    }
  }

  public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
    if($cmd->getName() == "addmoney"){
      if($sender->isOp() or $sender instanceof ConsoleCommandSender){
      if(count($args) < 2){
        $sender->sendMessage(C::RED . "Usage: /addmoney <player> <amount>");
      }
      else{
        if(is_numeric($args[1])) {
      $player = strtolower($args[0]);
      $bal = $args[1];
      $this->addMoney($player, $bal);
      $sender->sendMessage(C::GREEN . "Sucessfully added " . $bal . " Coins to " . $player . "s budget!");
    }
    else{
      $sender->sendMessage(C::RED . "That isnt a number silly");
    }
    }
    }
  }
    if($cmd->getName() == "bal"){
      if($sender instanceof Player){
      $cash = $this->myMoney(strtolower($sender->getName()));
      $sender->sendMessage(C::GREEN . "You have " . $cash . " Coins!");
    }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
  }
    if($cmd->getName() == "sellhand"){
      if($sender instanceof Player){
        $item = $sender->getInventory()->getItemInHand();
        $price = $this->checkPrice(str_replace(" ", "_", strtolower($item->getName())));
        $count = count($item->getCount());
        str_repeat($this->addMoney($sender->getName(), $price), $count);
        $sender->getInventory()->setItemInHand(Item::get(0,0,0));
        $sender->sendMessage(C::GREEN . "You sold " . str_replace(" ", "_", strtolower($item->getName())) . " for " . $price . " Coins!");
      }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
    }
    if($cmd->getName() == "takemoney"){
      if($sender->isOp()){
        $player = strtolower($args[0]);
        $bal = $args[1];
        $this->subtractMoney($player, $bal);
        $sender->sendMessage(C::GREEN . "Taken $" . $bal . " from " . $player . "!");
      }
      else{
        $sender->sendMessage(C::RED . "You dont have permission to use this command!");
      }
    }
    if($cmd->getName() == "pay"){
      if(count($args) < 2){
        $sender->sendMessage(C::RED . "Usage: /pay <player> <amount>");
      }
      else{
      $player = strtolower($args[0]);
      $bal = $args[1];
      $this->payPlayer($player, $bal, $sender->getName());
      $sender->sendMessage(C::GREEN . "Sucessfully payed " . $bal . " Coins to " . $player . "!");
    }
    }
  }
}
