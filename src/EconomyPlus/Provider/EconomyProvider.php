<?php
namespace EconomyPlus\Provider;

use pocketmine\utils\Config;

use EconomyPlus\EconomyPlus;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, December 2016
 */

class EconomyProvider{

  public function getInstance()
  {
    return EconomyPlus::getInstance();
  }

  public function getAllMoney()
  {
    return null;
  }

  public function getPath()
  {
    return null;
  }
}