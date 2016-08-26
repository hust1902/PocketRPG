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
  
  public $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  public function getOwner() {
    return $this->plugin;
  }
  // $args[0] = command, $args[1] = quest id, $args[2] = argument $args[3] = value
  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    if(strtolower($cmd->getName() == "quest")) {
      switch(strtolower($args[0])) {
        case "create":
          if($p->hasPermission("quests.command") || $p->hasPermission("quests.command.create")) {
            if(isset($args[1]) && is_numeric($args[1])) {
              $p->sendMessage(TF:: GREEN . "Quest " . $args[1] . "succesfully created");
              @mkdir($this->getDataFolder());
              $this->saveResource("quest.yml");
              $this->args[1] = new Config($this->getDataFolder() . "quests/" . $args[1]);
            } else {
              $p->sendMessage(TF:: RED . "Please choose a prefered ID number for your quest!");
            }
          } else {
            $p->sendMessage(TF:: RED . "You do not have permission to use this command!");
          }
        return true;
        break;
        
        case "edit":
          if($p->hasPermission("quests.command") || $p->hasPermission("quests.command.edit")) {
            if(isset($args[1], $args[2], $args[3]) && is_numeric($args[1])) {
              switch(strtolower($args[2])) {
                case "questname":
                  if(file_exists($this->getOwner()->getServer()->getDataPath() . "plugins/PocketRPG/quests/" . $args[1])) {
                    $questname = implode(" ", array_shift(array_shift(array_shift($args))));
                    $this->args[1]->set("QuestName", $questname);
                    $this->args[1]->save();
                    $p->sendMessage(TF:: GREEN . "You have succesfully set the quest name of " . $args[1] . " to " . $questname);
                  } else {
                    $p->sendMessage(TF:: RED . "That quest does not exist!");
                  }
                return true;
                break;
                
                case "questdescription":
                  if(file_exists($this->getOwner()->getServer()->getDataParth() . "plugins/PocketRPG/quests/" . $args[1])) {
                    $questdescription = implode(" ", array_shift(array_shift(array_shift($args))));
                    $this->args[1]->set("QuestDescription", $questdescription);
                    $this->args[1]->save()
                  }
              }
            }
          }
        break;
      }
    }
  }
}
