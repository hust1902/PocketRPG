<?php

namespace PocketRPG\commands;

use PocketRPG\Main;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;
use pocketmine\item\Item;

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
          if ($p->hasPermission ("quests.command") && is_numeric ($args [1]) && !file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
            @mkdir($this->getDataFolder () . "quests/");
            @file_put_contents ($this->getDataFolder () . "quests/" . $args [1] . ".yml", yaml_emit([
            "QuestName" => "",
            "QuestDescription" => "",
            "RequiredExpLvl" => "",
            "RequiredID" => "",
            "RequiredAmount" => "",
            "RewardID" => "",
            "RewardAmount" => "",
            "Started" => array (),
            "Finished" => array ()
            ]));
            $p->sendMessage (TF::GREEN . "You succesfully created the quest with quest ID " . $args [1] . ". Use /quest edit to modify it.");
          } else {
            $p->sendMessage (TF::RED . "The Quest ID must be numeric and you can't make two quests with the same ID!);
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
                    unset($args[0]);
                    unset($args[2]);
                    $questname = implode (" ", $args);
                    $quest->set ("QuestName", $questname);
                    $p->sendMessage (TF::GREEN . "You succesfully changed the quest name of " . $args [1] . " to " . $questname . ".");
                    $quest->save ();
                  } else {
                    $p->sendMessage (TF::RED . "There is no quest with that quest ID!");
                  }
                return true;
                break;
                
                case "questdescription":
                  if (file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
                    unset($args[0]);
                    unset($args[2]);
                    $questdescription = implode (" ", $args);
                    $quest->set ("QuestDescription", $questdescription);
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

        case "start":
          $quest = new Config ($this->getDataFolder () . "quests/" . $args [1] . ".yml");
          if (isset ($args [1]) && file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
            if (in_array ($p->getName (), $quest->get ("Started", array ())) || in_array ($p->getName (), $quest->get ("Finished", array ()))) {
              $p->sendMessage (TF::RED . "You have already started this quest!");
            } elseif($p->getExpLevel () >= $quest->get ("RequiredExpLvl")) {
              $player = $quest->get("Started", []);
              $player[] = $p->getName ();
              $quest->set("Started", $player);
              $quest->save ();
              $p->sendMessage (TF::GREEN . "Quest started: " . $quest->get ("QuestName"));
              $p->sendMessage (TF::GRAY . $quest->get ("QuestDescription"));
              $p->sendMessage (TF::GRAY . "To finish this quest you need " . $quest->get ("RequiredAmount") . " items of item ID " . $quest->get ("RequiredAmount"));
            } else {
              $p->sendMessage (TF::RED . "Your experience level is not high enough to start this quest!");
            }
          }
        return true;
        break;
 
        case "finish":
          $quest = new Config ($this->getDataFolder () . "quests/" . $args [1] . ".yml");
          if (isset ($args [1]) && file_exists ($this->getDataFolder () . "quests/" . $args [1] . ".yml")) {
            if (in_array ($p->getName (), $quest->get ("Started", array ())) && in_array ($p->getName (), $quest->get ("Finished", array ()))) {
              $p->sendMessage (TF::RED . "You have already finished this quest!");
            } elseif(in_array ($p->getName (), $quest->get ("Started", array ()))) {
              foreach ( $p->getInventory()->getContents()  as  $item) {
              if($item->getId() == $quest->get ("RequiredID") && $item->getCount() >= $quest->get ("RequiredAmount")){

                $p->getInventory ()->remove ($item);
                $player = $quest->get("Finished", []);
                $player[] = $p->getName ();
                $quest->set("Finished", $player);
                $quest->save();
                $p->sendMessage (TF::GREEN . "You completed quest " . $args[1] . "!");
                $p->sendMessage (TF::GREEN . "You have received a reward for finishing the quest!");
                $p->sendPopup (TF::AQUA . "You leveled up!");
                $items = Item::get($quest->get ("RewardID"), 0, $quest->get ("RewardAmount"));
                $p->getInventory ()->addItem ($items);
                $p->setExpLevel ($p->getExpLevel () + 1);
              } else {
                $p->sendMessage (TF::RED . "You do not have the required items in your inventory to finish this quest!");
              }
              }
            } else {
                $p->sendMessage (TF::RED . "You have finished this quest already!");
            }
          }
      return true;
      break;
  
      case "help":
       if ($p->hasPermission ("quests.command")) {
        $p->sendMessage (TF::GREEN . " --- " . TF::YELLOW . "Quest Help" . TF::GREEN . " --- ");
        $p->sendMessage (TF::GREEN . "/quest create <number>:" . TF::YELLOW . " Create a Quest with the given quest ID.");
        $p->sendMessage (TF::GREEN . "/quest edit <ID> <subcommand> <value>:" . TF:: YELLOW . " Edit a property of a Quest.");
        $p->sendMessage (TF::GREEN . "/quest start <ID>:" . TF::YELLOW . " Start a Quest with the given ID.");
        $p->sendMessage (TF::GREEN . "/quest finish <ID>:" . TF::YELLOW . " Finish a started quest with the given ID.");
        $p->sendMessage (TF::GREEN . "Subcommands:" . TF::YELLOW . " name, description, requiredexplvl, requiredid, requiredamount, rewardid, rewardamount.");
       }
      return true;
      break;

      }
    }
  }
}
