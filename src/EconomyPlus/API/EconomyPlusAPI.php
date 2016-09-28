<?php
namespace EconomyPlus\API;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use EconomyPlus\Main;
use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class EconomyPlusAPI extends PluginBase{

  public function __construct(Main $plugin)
  {
    $this->plugin = $plugin;
  }

  public function getMoney($player)
  {
    if($player instanceof Player)
    {
      $player = new EconomyPlayer($this->plugin, $player->getName());
      return $player->getMoney();
    }
    else if(is_string($player))
    {
      $player = new EconomyPlayer($this->plugin, $player);
      return $player->getMoney();
    }
    else if($player instanceof EconomyPlayer)
    {
      return $player->getMoney();
    }
    else{
      throw new \InvalidArgumentException("Arugment passed to EconomyPlusAPI::getMoney() must be type of string or pocketmine\Player");
    }
  }

  public function setMoney($player, int $amount)
  {
    if($player instanceof Player)
    {
      $player = new EconomyPlayer($this->plugin, $player->getName());
      $player->setMoney($amount);
    }
    else if($player instanceof String)
    {
      $player = new EconomyPlayer($this->plugin, $player);
      $player->setMoney($amount);
    }
    else if($player instanceof EconomyPlayer)
    {
      $player->setMoney($amount);
    }
    else{
      throw new \InvalidArgumentException("Arugment passed to EconomyPlusAPI::setMoney() must be type of string or pocketmine\Player");
    }
  }

  public function reduceMoney($player, int $amount)
  {
    if($player instanceof Player)
    {
      $player = new EconomyPlayer($this->plugin, $player->getName());
      $player->subtractMoney($amount);
    }
    else if(is_string($player))
    {
      $player = new EconomyPlayer($this->plugin, $player);
      $player->subtractMoney($amount);
    }
    else if($player instanceof EconomyPlayer)
    {
      $player->subtractMoney($amount);
    }
    else{
      throw new \InvalidArgumentException("Arugment passed to EconomyPlusAPI::reduceMoney() must be type of string or pocketmine\Player");
    }
  }

  public function addMoney($player, int $amount)
  {
    if($player instanceof Player)
    {
      $player = new EconomyPlayer($this->plugin, $player->getName());
      $player->addMoney($amount);
    }
    else if(is_string($player))
    {
      $player = new EconomyPlayer($this->player, $player);
      $player->addMoney($amount);
    }
    else if($player instanceof EconomyPlayer)
    {
      $player->addMoney($amount);
    }
    else{
      throw new \InvalidArgumentException("Arugment passed to EconomyPlusAPI::addMoney() must be type of string or pocketmine\Player");
    }
  }
}
