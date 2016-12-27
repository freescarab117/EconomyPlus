<?php
namespace EconomyPlus\Provider;

use pocketmine\utils\Config;

use EconomyPlus\EconomyPlus;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, December 2016
 */

class JsonProvider extends EconomyProvider{

  public function __construct(EconomyPlus $plugin)
  {
    $this->plugin = $plugin;
    $this->data = new Config($plugin->getDataFolder() . "/players.json", Config::JSON);
    $this->data->save();
    $this->data->reload();
  }

  public function getAllMoney()
  {
    return $this->data->getAll();
  }

  public function getPath()
  {
    return $this->plugin->getDataFolder() . "/players.json";
  }

  public function setMoney($player, int $ammount)
  {
    if($player instanceof pocketmine\Player)
    {
      $this->data->set(strtolower($player->getName()), (int) $ammount);
      $this->data->save();
    }
    elseif(is_string($player))
    {
      $this->data->set(strtolower($player), (int) $ammount);
      $this->data->save();
    }
    else{
      throw new \InvalidArgumentException("Arugment 1 passed to JsonProvider::addMoney() must be type of pocketmine\Player");
    }
  }

  public function getMoney($player)
  {
    if($player instanceof pocketmine\Player)
    {
      return (int) $this->data->get(strtolower($player->getName()));
    }
    elseif(is_string($player))
    {
      return (int) $this->data->get(strtolower($player));
    }
    else{
      throw new \InvalidArgumentException("Arugment 1 passed to JsonProvider::addMoney() must be type of pocketmine\Player");
    }
  }
}