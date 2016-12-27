<?php
namespace EconomyPlus\Commands;

use EconomyPlus\EconomyPlus;
use pocketmine\Player;
use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class BalanceCommand extends BaseCommand{

    private $plugin;

    public function __construct(EconomyPlus $plugin){
        parent::__construct("bal", $plugin);
        $this->plugin = $plugin;
        $this->setUsage(C::RED . "/bal <player>");
        $this->setDescription("Check your money balance!");
        $this->setAliases(array("mymoney","balance"));
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
    	if($sender instanceof Player){
    		$player->sendMessage(C::GREEN . "You have $" . EconomyPlus::getProvider()->getMoney($sender));
    	}
    }
}
