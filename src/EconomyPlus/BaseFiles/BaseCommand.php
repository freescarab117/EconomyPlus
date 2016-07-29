<?php

namespace EconomyPlus\BaseFiles;

use EconomyPlus\Main;
use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;

abstract class BaseCommand extends Command implements PluginIdentifiableCommand {

    private $plugin;

    public function __construct($name, Main $plugin){
        parent::__construct($name);
        $this->plugin = $plugin;
        $this->usageMessage = "";
    }

    public function getPlugin(){
        return $this->plugin;
    }

}
