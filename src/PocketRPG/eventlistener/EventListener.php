<?php

namespace PocketRPG\mylistener;

use PocketRPG\Main;
use pocketmine\living\Living;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\event\Event;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\BlockBreakEvent;
use pocketmine\event\BlockPlaceEvent;

class MyListener extends Main implements Listener {
  
  public function onFight(EntityDamageEvent $event) {
    if($event instanceof EntityDamageByEntityEvent && $event->getDamager() instanceof Player) {
        $hit = $event->getEntity();
        $damager = $event->getDamager();
            if($damager->hasPermission("class.assassin")) {
              if($damager->getItemInHand()->getId() == 388) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                $level->addParticle(new CriticalParticle($hitpos));
                $this->setDamage($this->getDamage() + 3);
              }
            } elseif($damager->hasPermission("class.mage")) {
              if($damage->getItemInHand()->getId() == 280) {
                $x = $hit-r>x;
                $y = $hit->y;
                $z = $hit->z;
                $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                $level->addParticle(new LavaParticle($hitpos));
                $this->setKnockBack(1);
                $hit->setOnFire(4);
                $this->setDamage($this->getDamage() + 3);
              }
            } elseif($damager->hasPermission("class.tanker")) {
              if($damager->getItemInHand()->getId() == 336) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                $level->addParticle(new ExplodeParticle($hitpos));
                $this->setKnockBack(3);
                $this->setDamage($this->getDamage() + 3);
              }
            } elseif($damager->hasPermission("class.warrior")) {
              if($damager->getItemInHand()->getId() == 267) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $hitpos = $hit->getPosition(new Vector3($x, $y, $z));
                $level->addParticle(new CriticalParticle($hitpos));
                $this->setKnockBack(2);
                $this->setDamage($this->getDamage() + 3);
              }
            }
        
    }
  }
  public function onItemHeld(PlayerItemHeldEvent $event) {
    $p = $event->getPlayer();
      if($p->getItemInHand() == 388) {
        if($p->hasPermission("class.assassin")) {
        $effect = Effect::getEffect(1)->setDuration(5)->setAmplifier(1)->setVisible(false);
        $p->addEffect($effect);
      }
    } elseif($p->getItemInHand() == 336) {
        if($p->hasPermission("class.tanker")) {
        $effect2 = Effect::getEffect(2)->setDuration(5)->setAmplifier(1)->setVisible(false);
        $effect = Effect::getEffect(11)->setDuration(5)->setAmplifier(1)->setVisible(false);
        $p->addEffect($effect);
        $p->addEffect($effect2);
        }
      }
    
  }

    public function onCraft(CraftItemEvent $event) {
      $event->setCancelled();
  }
  
  public function onBurn(FurnaceBurnEvent $event2) {
      $event2->setCancelled();
  }
  
  public function onSmelt(FurnaceSmeltEvent $event3) {
      $event3->setCancelled();
  }
  
  public function onDrop(PlayerDropEvent $event4) {
      $event4->setCancelled();
    }
  
  public function onDeath(PlayerDeathEvent $event) {
    $p = $event->getPlayer();
    $p->setKeepInventory();
    $p->setKeepExperience();
  }
}
