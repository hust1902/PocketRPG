<?php
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

class RPGcommands extends PluginBase implements CommandExecutor{
  
  public $plugin;
  public $config;

  public function __construct(Main $plugin) {
    parent::__construct($plugin)
    $this->plugin = $plugin;
  }

  public function onCommand(CommandSender $p, Command $cmd, $label, array $args) {
    switch(strtolower($cmd->getName())) {
      case "rpg":
        switch(strtolower($args[0])) {
          case "start":
          switch(strtolower($args[1])) {
          case "mage":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a mage!");
              $wand = Item::get(Item::STICK, 0, 1);
              $p->getInventory()->addItem($wand);
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.chosen");
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.mage");
              $p->switchLevel($this->getOwner()->config->get("RPGworld"));
            }
            return true;
            break;
            
          case "warrior":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a warrior!");
              $sword = Item::get(Item::IRON_SWORD, 0, 1);
              $p->getInventory()->addItem($sword);
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.chosen");
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.warrior");
              $p->switchLevel($this->getOwner()->config->get("RPGworld"));
            }
            return true;
            break;
            
          case "tanker":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a tanker!");
              $shield = Item::get(Item::BRICK, 0, 1);
              $p->getInventory()->addItem($shield);
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.chosen");
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.tanker");
              $p->switchLevel($this->getOwner->config->get("RPGworld"));
            }
            return true;
            break;
   
          case "assassin":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } elseif($p->hasPermission("class.special")) {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an assassin!");
              $knife = Item::get(Item::FEATHER, 0, 1);
              $p->getInventory()->addItem($knife);
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.chosen");
              $this->getServer()->dispatchCommand(new ConsoleCommandSender, "setuperm " . $p->getName() . " class.assassin");
              $p->switchLevel($this->getOwner()->config->get("RPGworld"));
            } else {
              $p->sendMessage(TF:: RED . "You do not have permission to access this class!");
            }
            return true;
            break;
          }
          break;
        }
    }
  }
}
  
