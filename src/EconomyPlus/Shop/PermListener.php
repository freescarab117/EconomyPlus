<?php
namespace EconomyPlus\Shop;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use EconomyPlus\Main;
use EconomyPlus\EconomyPlayer;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\tile\Sign;
use pocketmine\utils\TextFormat as C;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class PermListener extends PluginBase implements Listener{

  protected $plugin;

  public function __construct(Main $plugin, String $prefix)
  {
    $this->plugin = $plugin;
    $this->prefix = $prefix;
  }

  public function onCreate(SignChangeEvent $event){
    /*
    * Format
    * [Prefix]
    * [Cost]
    * [Perm]
    * [Perm]
    */
    $text = $event->getLines();
    if($text[0] === "[Perm]"){
    if(!$text[1] > 0){
      $event->getPlayer()->sendMessage($this->plugin->translate(C::RED . "Invalid Price"));
      return;
    }
    if(!$event->getPlayer()->isOp()){
      $event->getPlayer()->sendMessage($this->plugin->translate(C::RED . "You dont have permission to create economy shops!"));
      $event->setCancelled();
      return;
    }
    $event->setLine(0, $this->prefix);
    $event->setLine(1, "Price: " . $text[1]);
    $event->setLine(2, "Permission: ");
    $event->setLine(3, $text[3]);
    }
  }

  public function onInteract(PlayerInteractEvent $event){
    $player = $event->getPlayer();
    $eplayer = new EconomyPlayer($this->plugin, $player->getName());
    $blk = $event->getBlock();
    $tile = $player->getLevel()->getTile($blk);
    if($tile instanceof Sign){
      $text = $tile->getText();
      if($text[0] == $this->prefix){
        $price = substr($text[1], strpos($text[3], "Price: ") + 7);
        if(intval($price) > 0){
          if($text[2] == "Permission: "){
            $perm = $text[3];
            $eplayer->buyPerm($perm, $price);
            return true;
          }
        }
      }
    }
  }

  public function onBreak(BlockBreakEvent $event){
    $player = $event->getPlayer();
    $blk = $event->getBlock();
    $tile = $player->getLevel()->getTile($blk);
    if($tile instanceof Sign){
      $text = $tile->getText();
      if($text[0] == $this->prefix){
        $price = substr($text[1], strpos($text[3], "Price: ") + 7);
        if($price > 0){
          if($text[2] == "Permission: "){
            if(!$player->hasPermission("economyplus.shop.destroy")){
              $event->setCancelled(true);
              return false;
            }
            return true;
          }
        }
      }
    }
  }
}