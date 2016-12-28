<?php
namespace EconomyPlus\API;

use pocketmine\Player;

use EconomyPlus\EconomyPlus;

use EconomyPlus\Provider\EconomyProvider;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, December 2016
 */

class EconomyPlusAPI{

  protected $plugin, $provider;

  public function __construct(EconomyPlus $plugin, EconomyProvider $provider)
  {
    $this->plugin = $plugin;
    $this->provider = $provider;
  }

  public function setMoney($player, int $ammount)
  {
  	if($player instanceof Player)
  	{
  		$this->provider->setMoney($player->getName(), $ammount);
  		return true;
  	}
  	elseif(is_string($player))
  	{
  		$this->provider->setMoney($player, $ammount);
  		return true;
  	}
  	else
  	{
  		return false;
  	}
  }

  public function getMoney($player)
  {
  	if($player instanceof Player)
  	{
  		return $this->provider->getMoney($player->getName());
  	}
  	elseif(is_string($player))
  	{
  		return $this->provider->getMoney($player);
  	}
  	else
  	{
  		return null;
  	}
  }

  public function addMoney($player, int $ammount)
  {
  	if($player instanceof Player)
  	{
  		$this->provider->setMoney($player->getName(), $this->provider->getMoney($player->getName()) + $ammount);
  		return true;
  	}
  	elseif(is_string($player))
  	{
  		$this->provider->setMoney($player,  $this->provider->getMoney($player) + $ammount);
  	}
  	else
  	{
  		return false;
  	}
  }

  public function reduceMoney($player, int $ammount)
  {
  	if($player instanceof Player)
  	{
  		$this->provider->setMoney($player->getName(), $this->provider->getMoney($player->getName()) - $ammount);
  		return true;
  	}
  	elseif(is_string($player))
  	{
  		$this->provider->setMoney($player, $this->provider->getMoney($player) - $ammount);
  		return true;
  	}
  	else
  	{
  		return false;
  	}
  }
}
