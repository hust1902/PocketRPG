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
                                                   
namespace PocketRPG;

use PocketRPG\commands\QuestCommands;
use PocketRPG\commands\RpgCommands;
use PocketRPG\commands\PartyCommands;
use PocketRPG\eventlistener\EventListener;
use PocketRPG\tasks\ManaTask;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Level;
use pocketmine\level\Position;

class Main extends PluginBase implements Listener {
  
  public function onEnable() {
    $this->getLogger()->info(TF:: GREEN . "Enabling PocketRPG");
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getCommand("rpg")->setExecutor(new RpgCommands($this));
    $this->getCommand("quest")->setExecutor(new QuestCommands($this));
    $this->getCommand("party")->setExecutor(new PartyCommands($this));
    $this->getServer ()->getScheduler()->scheduleRepeatingTask (new ManaTask($this), 40);
    
    @mkdir($this->getDataFolder());
    $this->saveResource("config.yml");
    $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML);
    $this->playerclass = new Config($this->getDataFolder(). "class.yml", Config::YAML);
  }
  
  public function setClass(Player $player, $class){
      $this->playerclass->set($player->getName(),$class);
      $this->playerclass->set($player->getName().".class",true);
  }
  
  public function getClass(Player $player) {
    $class = $this->playerclass->get($player->getName());
    return $class;
  }

  public function onDisable() {
    $this->getLogger()->info(TF:: RED . "Disabling PocketRPG");
  }
}
