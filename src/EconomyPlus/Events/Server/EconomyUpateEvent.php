<?php
namespace EconomyPlus\Events\Server;

use EconomyPlus\Main;
use EconomyPlus\Events\EconomyServerEvent;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class EconomyUpdateEvent extends EconomyServerEvent{

	private $version;

	public static $handlerList;
	
	public function __construct(Main $plugin, $version){
		parent::__construct($plugin);
		$this->version = $version;
	}
	
	public function getPlayer(){
		return $this->player;
	}

	public function getVersion(){
		return $this->version;
	}
}
