<?php
namespace EconomyPlus;

use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;

use pocketmine\utils\Config;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class EconomyPlayer extends PluginBase{

  public function __construct(Main $plugin, String $player, $hasFile = null){
    $this->plugin = $plugin;
    $this->player = $player;
    $this->hasFile = $hasFile;
    $this->cfg = new Config($this->plugin->getDataFolder() . "/players.yml", Config::YAML);
  }

  public function getMoney(){
    return $this->cfg->get(strtolower($this->player));
  }

  public function subtractMoney(int $ammount){
    $money = $this->getMoney();
    if($money > $ammount){
      $this->setMoney($money - $ammount);
    }
    else{
      return false;
    }
  }

  public function setMoney(int $ammount){
    $this->cfg->set(strtolower($this->player), round($ammount));
    $this->cfg->save();
    return true;
  }

  public function addMoney(int $ammount){
    $this->setMoney($ammount + $this->getMoney());
    return true;
  }

  public function newPlayer(){
    $this->cfg->set(strtolower($this->player), $this->plugin->cfg->get("Default-Money"));
    $this->cfg->save();
    return true;
  }

  public function sendMessage(String $message){
    $this->plugin->getServer()->getPlayer($this->player)->sendMessage($message);
  }
}
