<?php
namespace EconomyPlus\Events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;

use EconomyPlus\EconomyPlus;
use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class EconomyPlayerEvent extends PluginEvent implements Cancellable{

	private $player;

	public static $handlerList;
	
	public function __construct(EconomyPlus $plugin, EconomyPlayer $player){
		parent::__construct($plugin);
		$this->player = $player;
	}
	
	public function getPlayer(){
		return $this->player;
	}
}
