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
use pocketmine\math\Vector3;
use pocketmine\level\particle\HugeExplodeParticle;

class HammerExplodeTask extends PluginTask implements Listener{
  
  public $plugin;
  public $p;
  
  public function __construct(EventListener $plugin, Entity $p) {
    parent::__construct($plugin);
    $this->plugin = $plugin;
    $this->entity = $p;
  }
  
  public function getOwner() {
    return $this->plugin;
  }
  
  public function onRun($tick) {
    $this->player->getLevel()->addParticle(new HugeExplodeParticle(new Vector3($this->entity->x, $this->entity->y, $this->entity->z)));
    $this->player->attack(6, EntityDamageEvent::CAUSE_ENTITY_ATTACK);
  }
}
