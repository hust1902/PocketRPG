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
namespace PocketRPG\tasks;
 
use PocketRPG\Main;
use pocketmine\scheduler\PluginTask;
use pocketmine\item\Item;

class ArrowObtainTask extends PluginTask {
    
  public $plugin;
  
  public function __construct(Main $plugin) {
    parent::__construct($plugin);
    $this->plugin = $plugin;
  }

  public function onRun($tick) {
    foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
      if($p->getLevel()->getName() == $this->plugin->config->get("RPGworld")) {
        if($this->plugin->playerclass->get($p->getName()) == "Archer" && !$p->getInventory()->contains(Item::get(Item::ARROW, 0, 5))) {
          $p->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
        }
      }
    }
  }
}
