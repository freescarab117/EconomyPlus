<?php
namespace EconomyPlus;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\Server;
use pocketmine\Player;

use EconomyPlus\EconomyPlus;

use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerDeathEvent;

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

  public function onLogin(PlayerPreLoginEvent $event)
  {
  	if($this->plugin->provider->getMoney($event->getPlayer()) != null)
  	{
  		$this->plugin->provider->setMoney($event->getPlayer(), (int) $this->plugin->config->get("Default-Money"));
  	}
  }
}
