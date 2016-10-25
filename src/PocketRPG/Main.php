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

use PocketRPG\events\QuestStartEvent;
use PocketRPG\events\QuestFinishEvent;
use PocketRPG\commands\QuestCommands;
use PocketRPG\commands\RpgCommands;
use PocketRPG\commands\PartyCommands;
use PocketRPG\eventlistener\EventListener;
use PocketRPG\tasks\ManaGainTask;
use PocketRPG\tasks\ArrowObtainTask;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\Player;

class Main extends PluginBase implements Listener {
  
  public function onEnable() {
    $this->getLogger()->info(TF:: GREEN . "Enabling PocketRPG");
    $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    $this->getCommand("rpg")->setExecutor(new RpgCommands($this));
    $this->getCommand("quest")->setExecutor(new QuestCommands($this));
    $this->getCommand("party")->setExecutor(new PartyCommands($this));
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new ManaGainTask($this), 40);
    $this->getServer()->getScheduler()->scheduleRepeatingTask(new ArrowObtainTask($this), 80); 
    @\mkdir($this->getDataFolder());
    $this->saveResource("config.yml");
    $this->config = new Config($this->getDataFolder(). "config.yml", Config::YAML);
    $this->playerclass = new Config($this->getDataFolder(). "class.yml", Config::YAML);
  }
  
  public function onDisable() {
    $this->getLogger()->info(TF:: RED . "Disabling PocketRPG");
  }
      ////////// API \\\\\\\\\\
  public function hasClass(Player $p) {
    if($this->playerclass->get($p->getName() . ".class") == true) {
      return true;
    }
  }
  
  public function setClass(Player $p, $class) {
      $this->playerclass->set($p->getName(), $class);
      $this->playerclass->set($p->getName() . ".class", true);
      $this->playerclass->save();
  }
  
  public function getClass(Player $p) {
    $class = $this->playerclass->get($p->getName());
    return $class;
  }
  
  public function unsetClass(Player $p){
    $this->playerclass->set($p->getName(). ".class", false);
    $this->playerclass->remove($p->getName());
    $this->playerclass->save();
  }
  public function hasQuestFinished(Player $p, $quest) {
    $this->quest = new Config("quests/" . $quest . ".yml");
    if(in_array($p->getName(), $this->quest->get("Finished", []))) {
      return true;
    }
  }
  public function hasQuestStarted(Player $p, $quest) {
    $this->quest = new Config("quests/" . $quest . ".yml");
    if(in_array($p->getName(), $this->quest->get("Started", []))) {
      return true;
    }
  }
  
  public function clearAllQuests(Player $p) {
    $quests = \scandir("quests/");
    foreach($quests as $quest) {
      if($quest != "." && $quest != "..") {
        $this->quest = new Config("quests/" . $quest);
        if(\is_array($this->quest->get("Finished"))) {
          $finished = $this->quest->get("Finished", array());
          unset($finished[array_search($p->getName(), $finished)]);
          $this->quest->set("Finished", $finished);
          $this->quest->save();
        } if(\is_array($this->quest->get("Started"))) {
          $started = $this->quest->get("Started", array());
          unset($started[array_search($p->getName(), $started)]);
          $this->quest->set("Started", $started);
          $this->quest->save();
        }
      }
    }
  }
  public function startQuest(Player $p, $questid) {
    $quest = new Config("quests/" . $questid . ".yml");
    $player = $quest->get("Started", []);
    $player[] = $p->getName();
    $quest->set("Started", $player);
    $quest->save();
    $this->getServer()->getPluginManager()->callEvent(new QuestStartEvent($this, $p, $questid));
  }
  public function finishQuest(Player $p, $questid) {
    $quest = new Config("quests/" . $questid . ".yml");
    $player = $quest->get("Finished", []);
    $player[] = $p->getName ();
    $quest->set("Finished", $player);
    $quest->save();
    $this->getServer()->getPluginManager()->callEvent(new QuestFinishEvent($this, $p, $questid));
  }
  
  public function meetsRequirements(Player $p, $requiredclass, $requiredexp, $requiredmana) {
    if($this->playerclass->get($p->getName()) === $requiredclass) {
      if($p->getExp() >= $requiredexp) {
        if($p->getFood() >= $requiredmana) {
          if(\in_array($p->getLevel()->getName(), $this->config->get("RPGworld", []))) {
            return true;
          }
        }
      }
    }
  }
}
