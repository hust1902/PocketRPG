<?php

namespace PocketRPG\eventlistener;

use PocketRPG\Main;
use PocketRPG\tasks\ExplodeTask;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as TF;
use pocketmine\utils\Config;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Event;
use pocketmine\math\Vector3;
use pocketmine\level\Position;
use pocketmine\level\Level;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\inventory\FurnaceSmeltEvent;
use pocketmine\event\inventory\FurnaceBurnEvent;
use pocketmine\level\particle\CriticalParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\HugeExplodeParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\EntityFlameParticle;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\Item;
use pocketmine\entity\Effect;

class EventListener extends PluginBase implements Listener {
  
  public function onFight(EntityDamageEvent $event) {
    if($event instanceof EntityDamageByEntityEvent) {
        $hit = $event->getEntity();
        $damager = $event->getDamager();
          if(!$damager instanceof Player){
            return false;
          } else {
            $level = $damager->getLevel();
            if($damager->getItemInHand()->getId() == Item::FEATHER && $level->getName() == $this->config->get("RPGworld")){
              if($damager->hasPermission("class.assassin")) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $level->addParticle(new CriticalParticle(new Vector3($x, $y, $z)));
                $event->setDamage(4);  //adds a critical particle and does extra damage
              }
            } elseif($damager->getItemInHand()->getId() == Item::STICK && $level->getName() == $this->config->get("RPGworld")) {
              if($damager->hasPermission("class.mage")) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $event->setKnockBack(0.6);
                $hit->setOnFire(5);
                $level->addParticle(new LavaParticle(new Vector3($x, $y, $z)));
                $event->setDamage(3); //sets the player on fire, does extra damage and adds a lava particle
              }
            } elseif($damager->getItemInHand()->getId() == Item::BRICK && $level->getName() == $this->config->get("RPGworld")) {
              if($damager->hasPermission("class.tanker")) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $level->addParticle(new HugeExplodeParticle(new Vector3($x, $y, $z)));
                $event->setKnockBack(1);
                $event->setDamage(2);  //sets knockback very high, does extra damage and adds an explosion particle
              }
            } elseif($damager->getItemInHand()->getId() == Item::IRON_SWORD && $level->getName() == $this->config->get("RPGworld")) {
              if($damager->hasPermission("class.warrior")) {
                $x = $hit->x;
                $y = $hit->y;
                $z = $hit->z;
                $level->addParticle(new CriticalParticle(new Vector3($x, $y, $z)));
                $event->setKnockBack(0.8);
                $event->setDamage(4);  //sets knockback high, does extra damage and adds a critical particle
              }
            } elseif($damager->getItemInHand()->getId() == Item::IRON_SHOVEL && $level->getName() == $this->config->get("RPGworld")) {
              if($damager->hasPermission("class.warrior")) {
                $explodetask = new ExplodeTask($this);
                $handler = $this->getServer()->getScheduler()->scheduleDelayedTask($explodetask, 30);
                $explodetask->setHandler($handler);
                $this->tasks[$explodetask->getTaskId()] = $explodetask->getTaskId(); //explosion in air task
                $level->addParticle(new ExplodeParticle(new Vector3($hit->x, $hit->y, $hit->z)));
                $event->setKnockBack(1.5);
                $event->setDamage(1);
              }
            }
         }
    }
  }
  public function onItemHeld(PlayerItemHeldEvent $event) {
    $p = $event->getPlayer();
    $level = $p->getLevel();
    if($level->getName() == $this->config->get("RPGworld") {
      if($p->getItemInHand()->getId() == Item::FEATHER && $level->getName() == $this->config->get("RPGworld")) {
        if($p->hasPermission("class.assassin")) {
        $effect = Effect::getEffect(1)->setDuration(240)->setAmplifier(1)->setVisible(false);
        $p->addEffect($effect); //gives assassin speed with feather
      }
    } elseif($p->getItemInHand()->getId() == Item::BRICK && $p->getExpLevel() >= 10) {
        if($p->hasPermission("class.tanker")&& $level->getName() == $this->config->get("RPGworld")) {
        $effect = Effect::getEffect(11)->setDuration(200)->setAmplifier(1)->setVisible(false);
        $p->addEffect($effect);  //gives tanker resistance if experience level is higher than 8 with brick
        }
    } elseif($p->getItemInHand()->getId() == Item::CLOCK && $p->getExpLevel() >= 10) {
        if($p->hasPermission("class.assassin") && $level->getName() == $this->config->get("RPGworld")) {
        $effect = Effect::getEffect(14)->setDuration(60)->setAmplifier(1)->setVisible(true);
        $p->addEffect($effect);
        $x = $p->x;
        $y = $p->y;
        $z = $p->z;
        $level->addParticle(new LavaParticle(new Vector3($x, $y, $z))); //Cloak of invisibility
        }
    } elseif($p->getItemInHand()->getId() == Item::BONE && $p->getExpLevel() >= 10) {
        if($p->hasPermission("class.mage") && $p->getLevel()->getName() == $this->config->get("RPGworld")) {
        $effect = Effect::getEffect(10)->setDuration(100)->setAmplifier(1)->setVisible(true);
        $p->addEffect($effect);
        $x = $p->x;
        $y = $p->y;
        $z = $p->z;
        $level->addParticle(new HeartParticle(new Vector3($x, $y + 2, $z))); //Bone of life
        }
    } elseif($p->getItemInHand()->getId() == Item::REDSTONE && $p->getExpLevel() >= 10) {
        if($p->hasPermission("class.warrior") && $p->getLevel()->getName() == $this->config->get("RPGworld")) {
        $effect = Effect::getEffect(5)->setDuration(200)->setAmplifier(1)->setVisible(true);
        $p->addEffect($effect);
        $x = $p->x;
        $y = $p->y;
        $z = $p->z;
        $level->addParticle(new EntityFlameParticle(new Vector3($x, $y+3, $z))); //Rage powder
        }
    } 
    if($p->getItemInHand()->getId() == Item::STICK) {
        $p->sendPopup(TF:: AQUA . "Wand\n" . TF:: GRAY . "Fireball - Mage");
    } elseif($p->getItemInHand()->getId() == Item::FEATHER) {
        $p->sendPopup(TF:: AQUA . "Dagger\n" . TF::GRAY . "Stab - Assassin");
    } elseif($p->getItemInHand()->getId() == Item::BRICK) {
      if($p->getExpLevel() >= 8) {
        $p->sendPopup(TF:: AQUA . "Shield\n" . TF::GRAY . "Slam + Resistance - Warrior");
      } else {
        $p->sendPopup(TF:: AQUA . "Shield\n" . TF::GRAY . "Slam - Warrior");
      }
    } elseif($p->getItemInHand()->getId() == Item::CLOCK) {
        $p->sendPopup(TF:: AQUA . "Cloak of Invisibility\n" . TF::GRAY . "Invisibility - Assassin");
    } elseif($p->getItemInHand()->getId() == Item::IRON_SWORD) {
        $p->sendPopup(TF:: AQUA . "Sword\n" . TF::GRAY . "Strike - Warrior");
    } elseif($p->getItemInHand()->getId() == Item::BONE) {
        $p->sendPopup(TF:: AQUA . "Bone of Life\n" . TF::GRAY . "Regeneration - Mage");
    } elseif($p->getItemInHand()->getId() == Item::REDSTONE) {
        $p->sendPopup(TF:: AQUA . "Rage Powder\n" . TF::GRAY . "Strength - Warrior");
    } elseif($p->getItemInHand()->getId() == Item::BOOK) {
        $p->sendPopup(TF:: AQUA . "Abilities Book");
        if($p->hasPermission("class.assassin")) {
          $p->sendMessage(TF:: GREEN . "---Assassin Abilities---");
          $p->sendMessage(TF:: AQUA . "Stab - Lvl. 0 - Dagger");
          $p->sendMessage(TF:: AQUA . "Invisibility - Lvl. 10 - Cloak of Invisibility");
          $p->sendMessage(TF:: AQUA . "Backstab - Lvl. 20 - Hook");
        } elseif($p->hasPermission("class.mage")) {
          $p->sendMessage(TF:: GREEN . "---Mage Abilities---");
          $p->sendMessage(TF:: AQUA . "Fireball - Lvl. 0 - Wand");
          $p->sendMessage(TF:: AQUA . "Regeneration - Lvl. 10 - Bone of life");
          $p->sendMessage(TF:: AQUA . "Ring of fire - Lvl. 20 - Ever burning Fire");
        } elseif($p->hasPermission("class.tanker")) {
          $p->sendMessage(TF:: GREEN . "---Tanker Abilities---");
          $p->sendMessage(TF:: AQUA . "Slam - Lvl. 0 - Shield");
          $p->sendMessage(TF:: AQUA . "Resistance - Lvl. 10 - Shield");
          $p->sendMessage(TF:: AQUA . "Crushing blow - Lvl. 20 - Iron Pallet");
        } elseif($p->hasPermission("class.warrior")) {
          $p->sendMessage(TF:: GREEN . "---Warrior Abilities---");
          $p->sendMessage(TF:: AQUA . "Strike - Lvl. 0 - Sword");
          $p->sendMessage(TF:: AQUA . "Strength - Lvl. 10 - Rage Powder");
          $p->sendMessage(TF:: AQUA . "Fissure - Lvl. 20 - Fissure Hammer");
        }
    } elseif($p->getItemInHand()->getId() == Item::IRON_SHOVEL) {
       $p->sendPopup(TF:: AQUA . "Fissure Hammer\n" . TF:: GRAY . "Fissure - Warrior");
    }
    }
  }
    public function onCraft(CraftItemEvent $event) {
      if($p->getLevel()->getName() == $this->config->get("RPGworld") {
        $event->setCancelled(); //denies any crafting, since that could get rid of important items
      }
  }
  
  public function onSmelt(FurnaceSmeltEvent $event) {
    if($p->getLevel()->getName() == $this->config->get("RPGworld") {
      $event->setCancelled(); //same counts for smelting items in a furnace
    }
  }
   public function onBurn(FurnaceBurnEvent $event) {
      if($p->getLevel()->getName() == $this->config->get("RPGworld") {
        $event->setCancelled(); //same counts for burning items in a furnace
      }
  }
  public function onDrop(PlayerDropItemEvent $event) {
    if($p->getLevel()->getName() == $this->config->get("RPGworld") {
      $event->setCancelled();  //same counts for dropping items out of your inventory
    }
  }
}
