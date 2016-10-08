<?php

namespace PocketRPG\events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use PocketRPG\commands\QuestCommands;
use PocketRPG\Main;

class QuestFinishEvent extends PluginEvent implements Cancellable {

  private $plugin;
  private $p;
  private $questid;

  public function __construct(Main $plugin, Player $p, $questid) {
    $this->player = $p;
    $this->plugin = $plugin;
    $this->questid = $questid;
  }

  public function getPlayer() {
    return $this->player;
  }

  public function getQuestId() {
    return $this->questid;
  }
}
