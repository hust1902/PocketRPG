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

use pocketmine\scheduler\PluginTask;
use PocketRPG\eventlistener\EventListener;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\level\particle\HugeExplodeParticle;

class ExplodeTask extends PluginTask implements Listener{
  
  private $plugin;
  private $p;

  public function __construct(EventListener $plugin, Entity $p) {
    parent::__construct($plugin);
    $this->plugin = $plugin;
    $this->entity = $p;
  }
  
  public function getPlugin() {
    return $this->plugin;
  }
  
  public function onRun($tick) {
    $this->player->getLevel()->addParticle(new HugeExplodeParticle(new Vector3($this->entity->x, $this->entity->y, $this->entity->z)));
    $this->player->attack(6, EntityDamageEvent::CAUSE_ENTITY_ATTACK);
  }
}
