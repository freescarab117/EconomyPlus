<?php
namespace EconomyPlus\Commands;

use EconomyPlus\BaseFiles\BaseCommand;
use EconomyPlus\Main;
use pocketmine\Player;
use pocketmine\command\CommandSender;

use pocketmine\utils\TextFormat as C;

use EconomyPlus\EconomyPlayer;

/* Copyright (C) ImagicalGamer - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 * Written by Jake C <imagicalgamer@outlook.com>, July 2016
 */

class TakeMoneyCommand extends BaseCommand{

    private $plugin;

    public function __construct(Main $plugin){
        parent::__construct("takemoney", $plugin);
        $this->plugin = $plugin;
        $this->setUsage(C::RED . "/takemoney <player> <ammount>");
        $this->setDescription("Take money from a player!");
    }

    public function execute(CommandSender $sender, $commandLabel, array $args) {
        if(!$sender->isOp()){
            $sender->sendMessage(C::RED . "You dont have permission to use this command");
            return;
        }
        if(!count($args) == 2){
            $sender->sendMessage(C::RED . "Usage: /takemoney <player> <ammount>");
            return;
        }
        if(!is_numeric($args[1])){
            $sender->sendMessage(C::RED . "Invalid ammount");
            return;
        }
        $player = new EconomyPlayer($this->plugin, $args[0]);
        if($player->subtractMoney($args[1]) == true){
        $sender->sendMessage(C::GREEN . "Taken $" . C::YELLOW . $args[1] . C::GREEN . " from " . C::YELLOW . $args[0]);
        return true;
        }
    }
}