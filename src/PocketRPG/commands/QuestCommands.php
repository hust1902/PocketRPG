<?php

namespace PocketRPG\commands;

use PocketRPG\Main;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;

class QuestCommands extends PluginBase implements Listener {
  // $args[0] = command, $args[1] = quest name, $args[2] = argument $args[3] = value
  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    if(strtolower($cmd->getName() == "quest")) {
      switch(strtolower($args[0])) {
        case "create":
          if($p->hasPermission("quests.command") || $p->hasPermission("quests.command.create")) {
            if(isset($args[1]) && is_numeric($args[1])) {
              $p->sendMessage(TF:: GREEN . "Quest " . $args[1] . "succesfully created");
              @mkdir($this->getDataFolder());
              $this->saveResource("quest.yml");
              $this->$args[1] = new Config($this->getDataFolder() . "quests/" . $args[1]);
            } else {
              $p->sendMessage(TF:: RED . "Please choose a prefered number for your quest!");
            }
          } else {
            $p->sendMessage(TF:: RED . "You do not have permission to use this command!");
          }
        return true;
        break;
        
        case "edit":
          if($p->hasPermission("quests.command") || $p->hasPermission("quests.command.edit")) {
            if(isset($args[1], $args[2], $args[3])) {
              switch(strtolower($args[2])) {
                case "questname":
                  if(!is_numeric($args[3])) {
                    if(file_exists($this->getServer()->getDataPath() . "plugins/PocketRPG/quests/" . $args[1])) {
                      $this->args[1]->set("QuestName", $args[3]);
                      $this->config->save();
                      $p->sendMessage(TF:: GREEN . "You have succesfully set the quest name of " . $args[1] . "to " . $args[3] . "");
                    } else {
                      $p->sendMessage(TF:: RED . "That quest does not exist!");
                    }
                  } else {
                    $p->sendMessage(TF:: RED . "You did not enter the command correctly");
                  }
                return true;
                break;
                
                case "questdescription":
                  //...
              }
            }
          }
        break;
      }
    }
  }
}
