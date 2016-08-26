<?php

namespace FWAcoreclasses;

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
  
  public function __construct(EventListener $plugin) {
    parent::__construct($plugin);
    $this->plugin = $plugin;
  }
  
  public function getPlugin() {
    return $this->plugin;
  }
  
  public function onRun($tick) {
    foreach($this->getOwner()->getServer()->getOnlinePlayers() as $p);
    $cause = $p->getLastDamageCause();
    if($cause instanceof EntityDamageByEntityEvent){
      if(!$cause->getDamager() instanceof Player){
        return false;
      } else {
        if($cause->getDamager()->hasPermission("class.warrior") and $cause->getDamager()->getItemInHand()->getId() == Item::IRON_SHOVEL) {
          $damager = $p->getLastDamageCause()->getDamager();
          $damager->getLevel()->addParticle(new HugeExplodeParticle(new Vector3($hit->x, $hit->y, $hit->z)));
          $p->attack(6, EntityDamageEvent::CAUSE_ENTITY_ATTACK);
        }
      }
    }
  }
}
