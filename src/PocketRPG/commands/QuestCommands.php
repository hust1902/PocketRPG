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
          if ($p->hasPermission ("quest.command") && is_numeric ($args [1])) {
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
          if($p->hasPermission("quests.command")) {
            if(isset($args[3]) && is_numeric($args[1])) {
              switch(strtolower($args[2])) {
                
                case "questname":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $questname = unset($args[0]);
                    $questname = unset($args[0]);
                    $questname = unset($args[0]);
                    $quest->set ("QuestName", implode (" ", $questname));
                    $p->sendMessage (TF::GREEN . "You succesfully changed the quest name of " . $args [1] . " to " . $questname . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "questdescription":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $questdescription = unset($args[0]);
                    $questdescription = unset($args[0]);
                    $questdescription = unset($args[0]);
                    $quest->set ("QuestDescription", implode (" ", $questdescription));
                    $p->sendMessage (TF::GREEN . "You succesfully changed the quest description of " . $args [1] . " to " . $questdescription . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "requiredid":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $quest->set ("RequiredID", $args [3]);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the required ID of " . $args [1] . " to " . $args[3] . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "requiredamount":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $quest->set ("RequiredAmount", $args [3]);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the required amount of " . $args [1] . " to " . $args[3] . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "rewardid":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $quest->set ("RewardID", $args [3]);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the reward ID of " . $args [1] . " to " . $args[3] . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "rewardamount":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $quest->set ("RewardAmount", $args [3]);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the reward amount of " . $args [1] . " to " . $args[3] . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                  
                case "requiredexplvl":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    $quest->set ("RequiredExpLvl", $args [3]);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the required ID of " . $args [1] . " to " . $args[3] . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
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
