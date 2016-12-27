<?php
namespace EconomyPlus\Commands;

use EconomyPlus\EconomyPlus;

use pocketmine\Player;

use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, September 2016
 */

class PayMoneyCommand extends BaseCommand{

    private $plugin;

    public function __construct(EconomyPlus $plugin){
        parent::__construct("pay", $plugin);
        $this->plugin = $plugin;
        $this->setUsage(C::RED . "/pay <player> <amount>");
        $this->setDescription("Take money from a player!");
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender instanceof Player){
            $sender->sendMessage(C::RED . $this->plugin->translate("INVALID-PERMISSION"));
            return;
        }
        if(!count($args) == 2){
            $sender->sendMessage(C::RED . "Usage: /pay <player> <amount>");
            return;
        }
        if(strtolower($sender->getName()) == strtolower($args[0])){
            $sender->sendMessage(C::RED . "Invalid Player");
            return;
        }
        if(!is_numeric($args[1])){
            $sender->sendMessage(C::RED . $this->plugin->translate("INVALID-AMOUNT"));
            return;
        }
        if(!$player2->getMoney() >= $args[1]){
            $sender->sendMessage(C::RED . $this->plugin->translate("INVALID-BALANCE"));
            return;
        }
        EconomyPlus::getProvider()->setMoney($sender, EconomyPlus::getProvider()->getMoney($sender));
        EconomyPlus::getProvider()->setMoney($args[0], EconomyPlus::getProvider()->getMoney($args[0]) + abs($args[1]));
        $sender->sendMessage(C::GREEN . "Payed $" . C::YELLOW . $args[1] . C::GREEN . " to " . C::YELLOW . abs($args[0]));
    }
}
