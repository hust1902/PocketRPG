<?php

namespace PocketRPG\commands;

use PocketRPG\Main;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor
use pocketmine\Player;
use pocketmine\Server;

class PartyCommands extends PluginBase implements CommandExecutor {
  
  public $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  public function getPlugin() {
    return $this->plugin;
  }
  
  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    if($cmd->getName() == "party") {
      switch(strtolower($args[0])) {
        
        case "invite":
          if (!file_exists (plugins/PocketRPG/" . $p->getName ()) {
            @mkdir($this->getDataFolder () . "plugins/PocketRPG/party");
            @file_put_contents ($this->getDataFolder () . "quests/" . $p->getName () . ".yml", yaml_emit([
              "Pending" => array (),
              "Allies" => array ()
            ]));
            //...
          } else {
            //...
          }
        return true;
        break;
        
        case "accept":
          
        return true;
        break;
        
        case "reject":
          
        return true;
        break;
        
        case "leave":
          
        return true;
        break;
        
        case "help":
          
        return true;
        break;
      }
    }
  }
}
