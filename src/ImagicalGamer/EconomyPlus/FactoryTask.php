<?php
namespace ImagicalGamer\EconomyPlus;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;

use pocketmine\scheduler\PluginTask;

use pocketmine\item\Item;
use pocketmine\tile\Dispenser;

use pocketmine\math\Vector3;
use pocketmine\tile\Tile;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, May 2016
 */

class FactoryTask extends PluginTask {
  public function __construct($plugin)
  {
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }
  
  public function onRun($tick)
  {
    $level = $this->plugin->getServer()->getDefaultLevel();
    $tiles = $level->getTiles();
    foreach($tiles as $t) {
      if($t instanceof Dispenser) {  
       $x = $t->getX();
       $y = $t->getY();
       $z = $t->getZ();
       $pos = new Vector3($x + 0.5, $y + 2, $z + 0.5);
       $level->dropItem($pos, Item::get(Item::EMERALD, 0, 1));
      }
    }
  }
}
