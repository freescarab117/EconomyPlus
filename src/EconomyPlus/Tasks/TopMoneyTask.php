<?php
namespace EconomyPlus\Tasks;

use pocketmine\Player;
use pocketmine\scheduler\AsyncTask;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\plugin\Plugin;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, August 2016
 */

class TopMoneyTask extends AsyncTask{

  public function __construct(String $player, Array $allMoney){
    $this->player = $player;
    $this->allMoney = $allMoney;
  }

  public function onRun(){
  }
  
  public function onCompletion(Server $server){
    $money = $server->getPluginManager()->getPlugin("EconomyPlus")->allMoney();
    arsort($money);
    $ret = array();
    $n = 0;
    $server->getPlayer($this->player)->sendMessage(C::GREEN . "EconomyPlus TopMoney!");
    $server->getPlayer($this->player)->sendMessage(C::YELLOW . "---------------------");
    foreach($money as $p => $m){
      $n++;
      $ret[$n] = [$p, $m];
      $message = json_encode($ret[$n]);
      $message = str_replace(["[", "]", '"'], "", $message);
      $message = str_replace(",", "§e: $", $message);
      if($p == strtolower($server->getPlayer($this->player)->getName())) {
      $server->getPlayer($this->player)->sendMessage(C::GREEN . C::BOLD . "* Your Rank: " . C::RESET . C::YELLOW . $n . "\n  ");
      }
    }
    $n = 0;
    foreach($money as $p => $m){
      $n++;
      $ret[$n] = [$p, $m];
      $message = json_encode($ret[$n]);
      $message = str_replace(["[", "]", '"'], "", $message);
      $message = str_replace(",", "§e: $", $message);
      if($n > 10){
        return $n = $n - 10;
      }
      $server->getPlayer($this->player)->sendMessage(C::GREEN . "* " . C::YELLOW . $n . ". " . C::GREEN .  $message);
    }
  }
}
