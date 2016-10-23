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
use pocketmine\utils\TextFormat as TF;
use pocketmine\plugin\PluginBase;

class RpgCommands extends PluginBase implements CommandExecutor{
  
  public $plugin;
  public $config;
  
  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }
  
  public function getOwner() {
     return $this->plugin;
  }
  
  public function onCommand(CommandSender $p, Command $cmd, array $args) {
    switch(strtolower($cmd->getName())) {
      case "rpg":
        switch(strtolower($args[0])) {
          case "start":
          $this->getOwner()->getServer()->loadLevel($this->getOwner()->config->get("RPGworld"));
          switch(\strtolower($args[1])) {
          case "mage":
            if($this->getOwner()->hasClass($p)){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a mage!");
              $wand = Item::get(Item::STICK, 0, 1);
              $wand->setCustomName(TF:: AQUA . "Wand\n" . TF:: GRAY . "Fireball - Mage");
              $p->getInventory()->addItem($wand);
              $book = Item::get(Item::BOOK, 0, 1);
              $book->setCustomName(TF:: AQUA . "Abilities Book");
              $p->getInventory()->addItem($book);
              $this->getOwner()->setClass($p, \strtolower($args[1]));
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
            
          case "warrior":
            if($this->getOwner()->hasClass($p)){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a warrior!");
              $sword = Item::get(Item::IRON_SWORD, 0, 1);
              $sword->setCustomName(TF:: AQUA . "Sword\n" . TF::GRAY . "Strike - Warrior");
              $p->getInventory()->addItem($sword);
              $book = Item::get(Item::BOOK, 0, 1);
              $book->setCustomName(TF:: AQUA . "Abilities Book");
              $p->getInventory()->addItem($book);
              $this->getOwner()->setClass($p, \strtolower($args[1]));
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
            
          case "tanker":
            if($this->getOwner()->hasClass($p)){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a tanker!");
              $shield = Item::get(Item::BRICK, 0, 1);
              $shield->setCustomName(TF:: AQUA . "Shield\n" . TF::GRAY . "Slam - Tanker");
              $p->getInventory()->addItem($shield);
              $book = Item::get(Item::BOOK, 0, 1);
              $book->setCustomName(TF:: AQUA . "Abilities Book");
              $p->getInventory()->addItem($book);
              $this->getOwner()->setClass($p, \strtolower($args[1]));
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
   
          case "assassin":
            if($this->getOwner()->hasClass($p)){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an assassin!");
              $knife = Item::get(Item::FEATHER, 0, 1);
              $knife->setCustomName(TF:: AQUA . "Dagger\n" . TF::GRAY . "Stab - Assassin");
              $p->getInventory()->addItem($knife);
              $book = Item::get(Item::BOOK, 0, 1);
              $book->setCustomName(TF:: AQUA . "Abilities Book");
              $p->getInventory()->addItem($book);
              $this->getOwner()->setClass($p, \strtolower($args[1]));
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;
            
          /*case "archer":
            if($this->getOwner()->hasClass($p)){
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an archer!");
              $bow = Item::get(Item::BOW, 0, 1);
              $p->getInventory()->addItem($bow);
              $arrow = Item::get(Item::ARROW, 0, 5);
              $p->getInventory()->addItem($arrow);
              $this->getOwner()->setClass($p, $args[1]);
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            }
            return true;
            break;*/
          }
          break;
          
          case "warp":
            if($this->getOwner()->hasClass($p)){
              $this->getOwner()->getServer()->loadLevel($this->getOwner()->config->get("RPGworld"));
              $p->sendMessage(TF::AQUA . "You warped to the RPG world!");
              $p->teleport($this->getOwner()->getServer()->getLevelByName($this->getOwner()->config->get("RPGworld"))->getSafeSpawn());
            } else {
              $p->sendMessage(TF::RED . "You haven't chosen a class yet!");
            }
            return true;
            break;
            
          case "reset":
            if($this->getOwner()->hasClass($p)) {
              $this->getOwner()->clearAllQuests($p);
              $this->getOwner()->unsetClass($p);
              $p->removeAllEffects();
              $p->getInventory()->clearAll();
              $default = $this->getOwner()->getServer()->getDefaultLevel();
              $p->teleport($default->getSafeSpawn());
              $p->setExpLevel(0);
              $p->sendMessage(TF:: YELLOW . "Your class has been reset.");
            } else {
              $p->sendMessage(TF:: RED . "You haven't chosen a class yet!");
            }
            return true;
            break;
            
          case "help":
            $p->sendMessage(TF::GREEN . "--- RPG Help ---");
            $p->sendMessage(TF::GREEN . "/rpg start <class> -" . TF::YELLOW . " Choose your class");
            $p->sendMessage(TF::YELLOW . "Classes available: tanker, assassin, mage, warrior");
            $p->sendMessage(TF::GREEN . "/rpg warp -" . TF::YELLOW . " Warp to RPG world");
            $p->sendMessage(TF::GREEN . "/rpg reset -" . TF::YELLOW . " Reset your class");
            $p->sendMessage(TF::RED . "(If you have a rare item in your inventory, make sure to put it in your chest before using this command!)");
            return true;
            break;
        }
    }
  }
}
