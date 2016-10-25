<?php
/*
*  _____           _        _   _____  _____   _____ 
* |  __ \         | |      | | |  __ \|  __ \ / ____|
* | |__) |__   ___| | _____| |_| |__) | |__) | |  __ 
* |  ___/ _ \ / __| |/ / _ \ __|  _  /|  ___/| | |_ |
* | |  | (_) | (__|   <  __/ |_| | \ \| |    | |__| |
* |_|   \___/ \___|_|\_\___|\__|_|  \_\_|     \_____|
*
*/     
namespace PocketRPG\commands;

use PocketRPG\Main;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\Player;

class PartyCommands extends PluginBase implements CommandExecutor {
  
  public $plugin;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  
  public function getOwner() {
    return $this->plugin;
  }
  
  public function onCommand(CommandSender $p, Command $cmd, array $args) {
    if($cmd->getName() == "party") {
     if(isset($args [1])) {
      switch(strtolower($args[0])) {
        
        case "invite":
          $party = new Config($this->getDataFolder() . "plugins/PocketRPG/party/" . $p->getName() . ".yml");
          $target = $this->getOwner()->getServer()->getPlayer($args[1]);
          if($target instanceof Player) {
            $player = $party->get("Pending", []);
            $player[] = $target->getName ();
            $party->set("Pending", $player);
            $party->save();
            $p->sendMessage(TF::GREEN . "A request has been sent to " . $target->getName() . "!");
            $target->sendMessage(TF::GREEN . "The Player " . $p->getName() . " has invited you to his/her party!\n" . TF::GREEN . "/party accept " . $p->getName() . TF::AQUA . ": to accept.\n" . TF::GREEN . "/party reject " . $p->getName() . TF::AQUA . ": to reject.");
          } else {
            $p->sendMessage(TF::RED . "That player is not online!");
          }
        return true;
        
        case "accept":
          if(\file_exists($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml")) {
            $party = new Config($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml");
            $target = $this->getOwner()->getServer()->getPlayer($args[1]);
            if(\in_array($p->getName(), $party->get("Pending", []))) {
              $p->sendMessage(TF::GREEN . "You have joined the party of " . $args[1] . "!");
              $player = $party->get("Allies", []);
              $player[] = $p->getName ();
              $party->set("Allies", $player);
              $party->save();
              $pending = $party->get("Pending");
              unset($pending[\array_search($p->getName(), $pending)]);
              $party->set("Pending", $pending);
              $party->save();
              if($target instanceof Player) {
                $target->sendMessage (TF::GREEN . "The player " . $p->getName() . " has accepted your invite!");
              }
            } else {
              $p->sendMessage(TF::RED . "That player did not invite you.");
            }
          } else {
            $p->sendMessage(TF::RED . "That player did not make a party yet!");
          }
        return true;
        
        case "reject":
          if(\file_exists($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml")) {
            $party = new Config($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml");
            $target = $this->getOwner()->getServer()->getPlayer($args[1]);
            if(\in_array($p->getName(), $party->get("Pending", []))) {
              $pending = $party->get("Pending");
              unset($pending[\array_search($p->getName(), $pending)]);
              $party->set("Pending", $pending);
              $party->save();
              if($target instanceof Player) {
                $target->sendMessage(TF::RED . "The player " . $p->getName() . " has rejected your invite!");
              }
            } else {
              $p->sendMessage(TF::RED . "That player did not invite you.");
            }
          } else {
            $p->sendMessage(TF::RED . "That player did not make a party yet!");
          }
        return true;
        
        case "leave":
          if(\file_exists($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml")) {
            $party = new Config($this->getDataFolder() . "plugins/PocketRPG/party/" . $args[1] . ".yml");
            if(\in_array($p->getName(), $party->get("Allies", []))) {
              $allies = $party->get("Allies");
              unset($allies[\array_search($p->getName(), $allies)]);
              $party->set("Allies", $allies);
              $party->save();
              $p->sendMessage(TF::GREEN . "You have succesfully left the party");
            } else {
              $p->sendMessage(TF::RED . "You are not in that party!");
            }
          } else {
            $p->sendMessage(TF::RED . "That player did not make a party yet!");
          }
        return true;
        
        case "help":
          $p->sendMessage(TF::GREEN . " --- " . TF::YELLOW . "Party help" . TF::GREEN . " --- ");
          $p->sendMessage(TF::GREEN . "/party invite <playername>:" . TF::AQUA . "Invite someone to your party.");
          $p->sendMessage(TF::GREEN . "/party accept <playername>:" . TF::AQUA . "Accept a party invite.");
          $p->sendMessage(TF::GREEN . "/party reject <playername>:" . TF::AQUA . "Reject a party invite.");
          $p->sendMessage(TF::GREEN . "/party leave <playername>:" . TF::AQUA . "Leave your current party.");
        return true;
      }
    }
   }
  }
}
