<?php

namespace PocketRPG\tasks;

use PocketRPG\Main;
use pocketmine\plugin\PluginTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;

class ManaTask extends PluginTask {

  public function __construct(Main $plugin) {
    parent::__construct ($plugin);
    $this->plugin = $plugin;
  }

  public function getPlugin () {
    return $this->plugin;
  }

  public function onRun ($currentTick) {
    foreach ($this->getPlugin()->getServer ()->getOnlinePlayers () as $p) {
      if($p->getLevel ()->getName () == $this->getPlugin ()->config->get ("RPGworld")) {
        if ($p->getFood () == 20) {
          $p->setFood (20);
        } else {
          $p->setFood ($p->getFood() + 1);
        }
      }
    }
  }
}
