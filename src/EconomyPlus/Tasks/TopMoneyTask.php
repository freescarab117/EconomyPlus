<?php
namespace EconomyPlus\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;
use pocketmine\item\Item;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class TopMoneyTask extends PluginBase{

  public function __construct(Main $plugin, String $player, Array $allMoney){
  	$this->plugin = $plugin;
    $this->player = $player;
    $this->allMoney = $allMoney;
  }

  public function getPlayer(){
  	if($this->player == "CONSOLE"){
  		return;
  	}
  	return $this->plugin->getServer()->getPlayer($this->player);
  }

  public function onRun(){
  	$money = $this->allMoney;
  	arsort($money);
  	$ret = array();
  	$n = 0;
  	$this->getPlayer()->sendMessage(C::GREEN . "EconomyPlus TopMoney!");
    $this->getPlayer()->sendMessage(C::YELLOW . "---------------------");
  	foreach($money as $p => $m){
  		$n++;
  		$ret[$n] = [$p, $m];
  		$message = json_encode($ret[$n]);
  		$message = str_replace(["[", "]", '"'], "", $message);
  		$message = str_replace(",", "§e: $", $message);
  		if($n > 10){
  			return $n = $n - 10;
  		}
  		$this->getPlayer()->sendMessage(C::GREEN . "* " . C::YELLOW . $n . ". " . C::GREEN .  $message);
  	}
  }
}