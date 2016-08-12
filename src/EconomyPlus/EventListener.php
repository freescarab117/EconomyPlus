<?php
namespace EconomyPlus;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;

use EconomyPlus\Main;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\event\block\BlockBreakEvent;

use pocketmine\utils\Config;

use pocketmine\tile\Sign;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class EventListener extends PluginBase implements Listener{

  protected $plugin;

  public function __construct(Main $plugin)
  {
    $this->plugin = $plugin;
  }

  public function onJoin(PlayerJoinEvent $event){
    $cfg = new Config($this->plugin->getDataFolder() . "/players.json", Config::JSON);
    $player = new EconomyPlayer($this->plugin, $event->getPlayer()->getName());
    if($cfg->exists($event->getPlayer()->getName(), true) == null){
      $player->newPlayer();
      return;
    }
  }

  public function onDeath(PlayerDeathEvent $event){
    if($event->getEntity() instanceof Player){
      if($event->getEntity()->getFinalDamageCause() instanceof Player){
        $cause = $event->getEntity()->getFinalDamageCause();
        $player = new EconomyPlayer($this->plugin, $cause->getName());
        $cause->addMoney($this->plugin->cfg->get("Death-Money"));
      }
    }
  }

  public function onBreak(BlockBreakEvent $event){
    if($event->getPlayer()->getLevel()->getTile($event->getBlock()) instanceof Sign){
      $tile = $event->getPlayer()->getLevel()->getTile($event->getBlock());
      $text = $tile->getText();
      if($text[0] === $this->plugin->shop){
        if(!$event->getPlayer()->hasPermission("economyplus.shop.destory")){
          $event->setCancelled(true);
        }
      }
      if($text[0] === $this->plugin->sell){
        if(!$event->getPlayer()->hasPermission("economyplus.shop.destory")){
          $event->setCancelled(true);
        }
      }
    }
  }
}