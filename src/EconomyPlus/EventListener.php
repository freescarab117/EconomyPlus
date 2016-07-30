<?php
namespace EconomyPlus;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;

use EconomyPlus\Main;

use pocketmine\event\player\PlayerJoinEvent;

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
    $player = new EconomyPlayer($this->plugin, $event->getPlayer()->getName());
    if(!$this->plugin->cfg->get(strtolower($event->getPlayer()->getName())) == null){
      $player->newPlayer();
    }
  }
}