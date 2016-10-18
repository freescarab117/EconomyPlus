<?php
namespace EconomyPlus;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;

use EconomyPlus\EconomyPlus;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerDeathEvent;

use pocketmine\event\block\BlockBreakEvent;

use pocketmine\utils\Config;

use pocketmine\tile\Sign;

use pocketmine\item\enchantment\Enchantment;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class EventListener extends PluginBase implements Listener{

  protected $plugin;

  public function __construct(EconomyPlus $plugin)
  {
    $this->plugin = $plugin;
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
}
