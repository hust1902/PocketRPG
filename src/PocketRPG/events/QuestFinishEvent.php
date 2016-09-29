<?php

namespace PocketRPG\events;

use pocketmine\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use PocketRPG\Main;

class QuestFinishEvent extends PluginEvent implements Cancellable {

  private $plugin;
  private $p;

  public function __construct(Main $plugin, Player $p) {
    $this->player = $p;
    $this->plugin = $plugin;
    parent::__construct($plugin);
  }

  public function getPlayer() {
    return $this->player;
  }
}
