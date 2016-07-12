<?php
namespace PocketRPG\commands;

use PocketRPG\Main;
use Pocketmine\item\Item;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;
use pocketmine\plugin\PluginBase;
use pocketmine\level\Level;
use pocketmine\Server;
use pocketmine\Player;

class RPGcommands extends PluginBase implements CommandExecutor{
  
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
              $p->getInventory->addItem($wand);
              $p->setPermission("class.chosen");
              $p->setPermission("class.mage");
              $p->switchLevel($cfglevel);
            }
            return true;
            break;
            
          case "warrior":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a warrior!");
              $sword = Item::get(Item::IRON_SWORD, 0, 1);
              $p->getInventory->addItem($sword);
              $p->setPermission("class.chosen");
              $p->setPermission("class.warrior");
              $p->switchLevel($cfglevel);
            }
            return true;
            break;
            
          case "tanker":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as a tanker!");
              $shield = Item::get(Item::MINECART, 0, 1);
              $p->getInventory->addItem($shield);
              $p->setPermission("class.chosen");
              $p->setPermission("class.tanker");
              $p->switchLevel($cfglevel);
            }
            return true;
            break;
   
          case "archer":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an archer!");
              $bow = Item::get(Item::BOW, 0, 1);
              $arrows = Item::get(Item::ARROW, 0, 128);
              $p->getInventory->addItem($bow);
              $p->getInventory->addItem($arrows);
              $p->setPermission("class.chosen");
              $p->setPermission("class.archer");
              $p->switchLevel($cfglevel);
            }
            return true;
            break;
  
          case "assassin":
            if($p->hasPermission("class.chosen")) {
              $p->sendMessage(TF:: RED . "You have already picked a class!");
            } else {
              $p->sendMessage(TF:: AQUA . "You have joined the world as an assassin!");
              $cloak = Item::get(Item::CLOCK, 0, 1);
              $knife = Item::get(Item::FEATHER, 0, 1);
              $p->getInventory->addItem($knife);
              $p->getInventory->addItem($cloak);
              $p->setPermission("class.chosen");
              $p->setPermission("class.assassin");
              $p->switchLevel($cfglevel);
            }
            return true;
            break;
          }
          break;
