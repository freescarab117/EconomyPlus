<?php
namespace EconomyPlus\Events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;

use EconomyPlus\Main;
use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class EconomyServerEvent extends PluginEvent implements Cancellable{
	private $issuer;
	public static $handlerList;
	public function __construct(Main $plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}
	
	public function getPlugin(){
		return $this->plugin;
	}
}
