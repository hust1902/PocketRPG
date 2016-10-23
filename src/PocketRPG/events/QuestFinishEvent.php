<?php
/*
*  _____           _        _   _____  _____   _____ 
* |  __ \         | |      | | |  __ \|  __ \ / ____|
* | |__) |__   ___| | _____| |_| |__) | |__) | |  __ 
* |  ___/ _ \ / __| |/ / _ \ __|  _  /|  ___/| | |_ |
* | |  | (_) | (__|   <  __/ |_| | \ \| |    | |__| |
* |_|   \___/ \___|_|\_\___|\__|_|  \_\_|     \_____|
*/
namespace PocketRPG\events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use PocketRPG\commands\QuestCommands;

class QuestFinishEvent extends PluginEvent implements Cancellable {
    
  private $plugin;
  private $p;
  private $questid;
  
  public function __construct(QuestCommands $plugin, Player $p, $questid) {
    parent::__construct($plugin->getOwner());
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
