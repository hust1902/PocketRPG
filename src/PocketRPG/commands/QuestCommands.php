<?php

namespace PocketRPG\commands;

use PocketRPG\Main;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;

class QuestCommands extends PluginBase implements CommandExecutor{
  
  public $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  public function getOwner() {
    return $this->plugin;
  }
 
  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    if(strtolower($cmd->getName() == "quest")) {
      switch(strtolower($args[0])) {
        case "create":
          if ($p->hasPermission ("quest.create") && is_numeric ($args [1])) {
            @mkdir($this->getDataFolder () . "quests/");
            @file_put_contents ($this->getDataFolder () . "quests/" . $args [1] . ".yml", yaml_emit([
            "QuestName" => "",
            "QuestDescription" => "",
            "RequiredExpLvl" => "",
            "RequiredID" => "",
            "RequiredAmount" => "",
            "RewardID" => "",
            "RewardAmount" => "",
            ]));
            $p->sendMessage (TF::GREEN . "You succesfully created the quest with quest ID " . $args [1] . ". Use /quest edit to modify it.");
          } else {
            $p->sendMessage (TF::RED . "The Quest ID must be numeric!");
          }
        return true;
        break;
        
        case "edit":
          $quest = new Config ($this->getDataFolder () . "quests/" . $args [1] . ".yml");
          if($p->hasPermission("quests.command") || $p->hasPermission("quests.command.edit")) {
            if(isset($args[3]) && is_numeric($args[1])) {
              switch(strtolower($args[2])) {
                
                case "questname":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $questname = array_shift (array_shift (array_shift ($args)));
                    $quest->set ("QuestName", implode (" ", $questname));
                  }
                return true;
                break;
                
                case "questdescription":

                return true;
                break;
                
                case "requiredid":

                return true;
                break;
                
                case "requiredamount":
                return true;
                break;
                
                case "rewardid":
                return true;
                break;
                
                case "rewardamount":
                return true;
                break;
                  
                case "finishmsg":
                return true;
                break;
                
                case "cantfinishmsg":
                return true;
                break;
              }
            }
          }
        break;
      }
    }
  }
}
