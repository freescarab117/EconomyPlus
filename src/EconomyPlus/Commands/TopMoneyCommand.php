<?php
namespace EconomyPlus\Commands;

use EconomyPlus\BaseFiles\BaseCommand;
use EconomyPlus\EconomyPlus;
use pocketmine\Player;
use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

use EconomyPlus\EconomyPlayer;
use EconomyPlus\Tasks\TopMoneyTask;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, August 2016
 */

class TopMoneyCommand extends BaseCommand{

    private $plugin;

    public function __construct(EconomyPlus $plugin){
        parent::__construct("topmoney", $plugin);
        $this->plugin = $plugin;
        $this->setDescription("View the topmoney list!");
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender instanceof Player){
            $sender->sendMessage(C::RED . $this->plugin->translate("INVALID-PERMISSION"));
            return;
        }
        $this->plugin->getServer()->getScheduler()->scheduleAsyncTask($task = new TopMoneyTask($sender->getName(), $this->plugin->allMoney()));
    }
}
