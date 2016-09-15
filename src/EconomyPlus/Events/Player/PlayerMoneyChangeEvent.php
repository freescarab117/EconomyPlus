<?php
namespace EconomyPlus\Events\Player;

use EconomyPlus\Main;
use EconomyPlus\EconomyPlayer;
use EconomyPlus\Events\EconomyPlayerEvent;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class PlayerMoneyChangeEvent extends EconomyPlayerEvent{

	private $player;
	private $plugin;
	private $amount;
	private $reason;
	
	public function __construct(Main $plugin, EconomyPlayer $player, Int $amount, $reason = EconomyPlayer::UNKNOWN){
		parent::__construct($plugin, $player);
		$this->player = $player;
		$this->amount = $amount;
	}
	
	public function getPlayer(){
		return $this->player;
	}

	public function getAmount(){
		return (int) $this->amount;
	}

	public function getReason(){
		return $this->reason;
	}
}
