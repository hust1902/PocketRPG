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
use Pocketmine\item\Item;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionAttachment;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\Server;
use pocketmine\Player;

class RpgCommands extends PluginBase implements CommandExecutor{
  
  public $plugin;
  public $config;

  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function getOwner() {
     return $this->plugin;
  }

  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    switch(strtolower($cmd->getName())) {
      case "rpg":
        switch(strtolower($args[0])) {
          case "start":
          $this->getOwner()->getServer()->loadLevel($this->getOwner()->config->get("RPGworld"));
          switch(strtolower($args[1])) {
          case "mage":
            if($this->getOwner()->playerclass->get($p->getName().".class") === true){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a mage!");
              $wand = Item::get(Item::STICK, 0, 1);
              $p->getInventory()->addItem($wand);
              $book = Item::get (Item::BOOK, 0, 1);
              $p->getInventory ()->addItem ($book);
              $this->getOwner()->setClass($p, $args[1]);
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
            
          case "warrior":
            if($this->getOwner()->playerclass->get($p->getName().".class") === true){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a warrior!");
              $sword = Item::get(Item::IRON_SWORD, 0, 1);
              $p->getInventory()->addItem($sword);
              $book = Item::get (Item::BOOK, 0, 1);
              $p->getInventory ()->addItem ($book);
              $this->getOwner()->setClass($p, $args[1]);
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
            
          case "tanker":
            if($this->getOwner()->playerclass->get($p->getName().".class") === true){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a tanker!");
              $shield = Item::get(Item::BRICK, 0, 1);
              $p->getInventory()->addItem($shield);
              $book = Item::get (Item::BOOK, 0, 1);
              $p->getInventory ()->addItem ($book);
              $this->getOwner()->setClass($p, $args[1]);
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
   
          case "assassin":
            if($this->getOwner()->playerclass->get($p->getName().".class") === true){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an assassin!");
              $knife = Item::get(Item::FEATHER, 0, 1);
              $p->getInventory()->addItem($knife);
              $book = Item::get (Item::BOOK, 0, 1);
              $p->getInventory ()->addItem ($book);
              $this->getOwner()->setClass($p, $args[1]);
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
          }
          break;

          case "warp":
            if($this->getOwner()->playerclass->get($p->getName().".class") === true){
              $this->getOwner()->getServer()->loadLevel($this->getOwner()->config->get("RPGworld"));
              $p->sendMessage (TF::AQUA . "You warped to the RPG world!");
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            } else {
              $p->sendMessage(TF::RED . "You haven't chosen a class yet!");
            }
        }
    }
  }
}
  
