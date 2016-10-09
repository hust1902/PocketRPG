<?php

namespace PocketRPG\tasks;

use PocketRPG\Main;
use pocketmine\scheduler\PluginTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\item\Item;

class ArrowObtainTask extends PluginTask {

  public function __construct(Main $plugin) {
    parent::__construct ($plugin);
    $this->plugin = $plugin;
  }

  public function getPlugin () {
    return $this->plugin;
  }

  public function onRun ($currentTick) {
    foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $p) {
      if($p->getLevel()->getName() == $this->getPlugin()->config->get("RPGworld")) {
        if($this->getPlugin()->playerclass->get($p->getName()) == "Archer" && !$p->getInventory()->contains(Item::get(Item::ARROW, 0, 5))) {
          $p->getInventory()->addItem(Item::get(Item::ARROW, 0, 1));
        }
      }
    }
  }
}
