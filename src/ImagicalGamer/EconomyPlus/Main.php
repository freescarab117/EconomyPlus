<?php
namespace ImagicalGamer\EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use pocketmine\utils\TextFormat as C;
use pocketmine\utils\Config;

use pocketmine\scheduler\PluginTask;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\command\{Command, CommandSender};
use pocketmine\command\ConsoleCommandSender;

use pocketmine\item\Item;
use pocketmine\tile\Dispenser;

use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\StringTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;

use pocketmine\math\Vector3;

use pocketmine\block\Block;
use pocketmine\tile\Tile;

use pocketmine\inventory\DropperInventory;

use ImagicalGamer\EconomyPlus\FactoryTask;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, May 2016
 */

class Main extends PluginBase implements Listener{

  private $factory = array();

  public function onEnable(){
    @mkdir($this->getDataFolder());
    $this->saveResource("/config.yml");
    $this->saveResource("/items.yml");
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->save();
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new FactoryTask($this), 700);
    $this->getLogger()->info(C::GREEN . "Money Data Found!");
  }

  public function addMoney(string $player, float $bal){
    $balence = $this->myMoney($player);
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->set(strtolower($player),$balence + $bal);
    $money->save();
  }

  public function myMoney(string $player): float{
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $amount = $money->get(strtolower($player));
    return $amount;
  }

  public function checkPrice(string $item): float{
    $items = new Config($this->getDataFolder() . "/items.yml", Config::YAML);
    $price = $items->get(strtolower($item));
    return $price;
  }

  public function subtractMoney(string $player, float $bal){
    $ammount = $this->myMoney($player);
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $money->set(strtolower($player),$ammount - $bal);
    $money->save();
  }

  public function createFactory(Player $player){
    $pos = new Vector3($player->getX(), $player->getY(), $player->getZ());
    $x = $player->getX();
    $y = $player->getY();
    $z = $player->getZ();
    $level = $player->getLevel();
    $level->setBlock($pos, Block::get(125));
        $nbt = new CompoundTag(" ", [
            new ListTag("Items", []),
            new StringTag("id", Tile::DISPENSER),
            new IntTag("x", $x),
            new IntTag("y", $y),
            new IntTag("z", $z)
        ]);
        $block = $player->getLevel()->getBlock($pos);
        $level = $player->getLevel();
        $dropper = Tile::createTile("Dispenser",$player->getLevel()->getChunk($block->getX() >> 4, $block->getZ() >> 4), $nbt);
        $level->addTile($dropper);
  }

  public function payPlayer(string $player, float $bal, string $sender){
    if($this->myMoney($sender) >= $bal){
      $this->addMoney($player, $bal);
      $this->subtractMoney($sender, $bal);
    }
  }

  public function onJoin(PlayerJoinEvent $event){
    $player = $event->getPlayer();
    $money = new Config($this->getDataFolder() . "/players.yml", Config::YAML);
    $def = 1000;
    if($money->get(strtolower($player->getName())) == null){
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
    if($entity instanceof Player){
      $amount = $this->myMoney($entity->getName()) / 0.05;
      $this->subtractMoney($entity->getName(), $amount);
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
        if($price == null){
          $sender->sendMessage(C::RED . "That Item doesnt have a price!");
        }
        else{
        $count = count($item->getCount());
        $count = $item->getCount();
        $amount = $price * $count;
        $this->addMoney($sender->getName() , $amount);
        $sender->getInventory()->setItemInHand(Item::get(0,0,0));
        $sender->sendMessage(C::GREEN . "You sold " . $count . " of " . str_replace(" ", "_", strtolower($item->getName())) . " for " . $price . " Coins!");
      }
      }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
    }
    if($cmd->getName() == "factory"){
      if($sender instanceof Player){
        if(in_array($sender->getName(), $this->factory)){
          $sender->sendMessage(C::RED . "You already have a factory!");
        }
        else{
          array_push($this->factory, $sender->getName());
          $sender->sendMessage(C::GREEN . "Creating Factory...");
          $this->createFactory($sender);
        }
        
      }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
    }
    if($cmd->getName() == "takemoney"){
      if($sender->isOp()){
        if(count($args) < 2){
          $sender->sendMessage(C::RED . "Usage: /takemoney <player> <amount>");
        }
        else{
        $player = strtolower($args[0]);
        $bal = $args[1];
        $this->subtractMoney($player, $bal);
        $sender->sendMessage(C::GREEN . "Taken $" . $bal . " from " . $player . "!");
      }
    }
      else{
        $sender->sendMessage(C::RED . "You dont have permission to use this command!");
      }
    }
    if($cmd->getName() == "debt"){
      if($sender instanceof Player){
        if($this->myMoney($sender->getName()) < 0){
        $sender->sendMessage(C::RED . "You are " . $this->myMoney($sender->getName()) . " Coins in Debt!");
      }
      else{
        $sender->sendMessage(C::GREEN . "Your not in debt!");
      }
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
    if($cmd->getName() == "payme"){
      if($sender instanceof Player){
        if(in_array($sender->getName(), $this->factory)){
            $item = $sender->getInventory()->getItemInHand();
            $count = $item->getCount();
            $amount = rand(500,1000) * $count;
            $this->addMoney($sender->getName() , $amount);
            $sender->sendMessage(C::GREEN . "You have collected your pay!");
            $sender->getInventory()->setItemInHand(Item::get(0,0,0));
          }
          else{
          $sender->sendMessage(C::RED . "You must have a factory to collet pay!");
        }
        }
              else{
          $sender->sendMessage(C::RED . "Please run this command in-game!");
        }
      }

              if($cmd->getName() == "redeem"){
      if($sender instanceof Player){
        if($sender->getInventory()->getItemInHand()->getName() == C::GREEN . C::BOLD . "GiftCard"){
          $amount = rand(500,2000);
          $this->addMoney($sender->getName(), $amount);
          $sender->getInventory()->setItemInHand(Item::get(0,0,0));
          $sender->sendMessage(C::GREEN . "You have redeemed a GiftCard for " . $amount . " Coins!");
        }
        else{
          $sender->sendMessage(C::RED . "Your not holding a GiftCard!");
        }
      }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
    }

        if($cmd->getName() == "slots"){
      if($sender instanceof Player){
        $price = 25;
        if($this->myMoney($sender->getName()) >= $price){
          $sender->sendMessage(C::GREEN . "Your Playing Slots!");
          sleep(1);
          $sender->sendMessage(C::RED . C::BOLD . "3");
          sleep(1);
          $sender->sendMessage(C::RED . C::BOLD . "2");
          sleep(1);
          $sender->sendMessage(C::RED . C::BOLD . "1");
          $this->subtractMoney($sender->getName(), $price);
          $prize = rand(1,500);
          $sender->sendMessage(C::GREEN . "You won " . C::YELLOW . $prize . C::GREEN . " Coins playing EconomyPlus Slots!");
          $this->addMoney($sender->getName(),$prize);
        }
        else{
          $sender->sendMessage(C::RED . "It cost 25 Coins to play Slots!");
        }
      }
      else{
        $sender->sendMessage(C::RED . "Please run this command in-game!");
      }
    }
    }
  }
