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
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerExperienceChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\inventory\PlayerInventory;
use pocketmine\entity\human;

class EventListener extends Main implements Listener {

  public $plugin;

  public function __construct(Main $plugin) {
    $this->plugin = $plugin;
  }

  public function getOwner() {
     return $this->plugin;
  }
  public function onQuit (PlayerQuitEvent $event) {
    $p = $event->getPlayer ();
    $party = new Config ($this->getDataFolder () . "plugins/PocketRPG/party/" . $p->getName () . ".yml");
    unlink ($this->getDataFolder() . "plugins/PocketRPG/party/" . $p->getName() . ".yml");
  }

  public function onJoin (PlayerJoinEvent $event) {
    $p = $event->getPlayer ();
    if ($p instanceof Player) {
      @mkdir($this->getDataFolder () . "plugins/PocketRPG/party/");
      @file_put_contents ($this->getDataFolder () . "plugins/PocketRPG/party/" . $p->getName () . ".yml", yaml_emit([
      "Pending" => array (),
      "Allies" => array ()
      ]));
    }
    if($p->getLevel()->getName() == $this->getOwner()->config->get("RPGworld")) {
      $p->setMaxHealth($p->getXpLevel() * 0.20 + 20);
      $p->setHealth($p->getXpLevel() * 0.20 + 20);
    }
  }

  public function onFight(EntityDamageEvent $event) {
    if($event instanceof EntityDamageByEntityEvent) {
        $hit = $event->getEntity();
        $damager = $event->getDamager();
        if($hit instanceof Player && $damager instanceof Player) {
          $hitparty = new Config ($this->getDataFolder () . "plugins/PocketRPG/party/" . $hit->getName() . ".yml");
          $damagerparty = new Config ($this->getDataFolder () . "plugins/PocketRPG/party/" . $damager->getName() . ".yml");
          if(in_array ($damager->getName (), $hitparty->get ("Allies", array ())) || in_array ($damager->getName (), $damagerparty->get ("Allies", array ()))) {
            $event->setCancelled();
          } 
        }
        
        if(!$damager instanceof Player){
            return false;
        } else {
            if ($damager->getFood() == 0) {
            $event->setCancelled ();
            
            } else {
                $level = $damager->getLevel();
            
                if($damager->getItemInHand()->getId() == Item::FEATHER && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")){
                  if($this->getOwner()->playerclass->get($damager->getName()) === "assassin"){
                    if ($damager->getFood() >= 1){
                      $x = $hit->x;
                      $y = $hit->y;
                      $z = $hit->z;
                      $level->addParticle(new CriticalParticle(new Vector3($x, $y, $z), 5));
                      $event->setDamage(4);
                      $damager->setFood ($damager->getFood () - 1);
                      $damager->sendPopup (TF::AQUA . "-1 Mana");
                    }
                  }
                } //Dagger
            
                elseif($damager->getItemInHand()->getId() == Item::STICK && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
                  if($this->getOwner()->playerclass->get($damager->getName()) === "mage"){
                    if($damager->getFood () >= 1) {
                      $x = $hit->x;
                      $y = $hit->y;
                      $z = $hit->z;
                      $event->setKnockBack(0.6);
                      $hit->setOnFire(5);
                      $level->addParticle(new LavaParticle(new Vector3($x, $y, $z), 5));
                      $event->setDamage(3);
                      $damager->setFood ($damager->getFood () - 1);
                      $damager->sendPopup (TF::AQUA . "-1 Mana");
                    }
                  }
                } //Wand
            
                elseif($damager->getItemInHand()->getId() == Item::BRICK && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
                  if($this->getOwner()->playerclass->get($damager->getName()) === "tanker"){
                    if ($damager->getFood () >= 1) {
                      $x = $hit->x;
                      $y = $hit->y;
                      $z = $hit->z;
                      $level->addParticle(new HugeExplodeParticle(new Vector3($x, $y, $z)));
                      $event->setKnockBack(1);
                      $event->setDamage(2);
                      $damager->setFood ($damager->getFood () - 1);
                      $damager->sendPopup (TF::AQUA . "-1 Mana");
                    }
                  }
                } //Shield
            
                elseif($damager->getItemInHand()->getId() == Item::IRON_SWORD && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
                  if($this->getOwner()->playerclass->get($damager->getName()) === "warrior"){
                    if ($damager->getFood () >= 1) {
                      $x = $hit->x;
                      $y = $hit->y;
                      $z = $hit->z;
                      $level->addParticle(new CriticalParticle(new Vector3($x, $y, $z), 5));
                      $event->setKnockBack(0.8);
                      $event->setDamage(4);
                      $damager->setFood ($damager->getFood () - 1);
                      $damager->sendPopup (TF::AQUA . "-1 Mana");
                    }
                  }
                } //Sword
            
                elseif($damager->getItemInHand()->getId() == Item::IRON_SHOVEL && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
                  if($this->getOwner()->playerclass->get($damager->getName()) === "warrior"){
                    if ($damager->getFood () >= 1) {
                      $this->getOwner ()->getServer()->getScheduler()->scheduleDelayedTask(new ExplodeTask($this, $hit), 20);
                      $level->addParticle(new ExplodeParticle(new Vector3($hit->x, $hit->y, $hit->z)));
                      $event->setKnockBack(1.5);
                      $event->setDamage(1);
                      $damager->sendPopup (TF::AQUA . "-1 Mana");
                    }
                  }
                } //Hammer (WIP)

                elseif($damager->getItemInHand()->getId() == Item::IRON_HOE && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
                  if($this->getOwner()->playerclass->get($damager->getName()) === "assassin"){
                    if ($damager->getFood () >= 8 && $damager->getXpLevel () >= 20) {
                      $level->addParticle(new LavaParticle(new Vector3($damager->x, $damager->y, $damager->z), 4));
                      $event->setKnockBack(0);
                      $event->setDamage(7);
                      $damager->sendPopup (TF::AQUA . "-8 Mana");
                      $damager->setFood ($damager->getFood () - 8);
                      $hitlocation = new Vector3 ($hit->x, $hit->y, $hit->z);
                      $damager->teleport ($hitlocation);
                    }
                  }
                } //Hook
            }
         }
        
         if ($damager->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
           $event->setDamage ($event->getDamage () + ($damager->getXpLevel() * 0.20));
         }
     }
  }
  public function onItemHeld(PlayerItemHeldEvent $event) {
    $p = $event->getPlayer();
    $level = $p->getLevel();
    if($level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
      if($p->getItemInHand()->getId() == Item::FEATHER) {
        if($this->getOwner()->playerclass->get($p->getName()) === "assassin"){
          if ($p->getFood () >= 1) {
            $effect = Effect::getEffect(1)->setDuration(240)->setAmplifier(1)->setVisible(false);
            $p->addEffect($effect); //gives assassin speed with feather
            $p->setFood ($p->getFood () - 1);
            $p->sendPopup (TF::AQUA . "-1 Mana");
          }
        }
      } //Dagger speed
    
      elseif($p->getItemInHand()->getId() == Item::BRICK && $p->getExpLevel() >= 10) {
        if($this->getOwner()->playerclass->get($p->getName()) === "assassin" && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
          if($p->getFood () >= 5) {
            $effect = Effect::getEffect(11)->setDuration(200)->setAmplifier(1)->setVisible(false);
            $p->addEffect($effect);  //gives tanker resistance if experience level is higher than 8 with brick
            $p->setFood ($p->getFood () - 5);
            $p->sendPopup (TF::AQUA . "-5 Mana");
          }
        }
      } //Shield resistance
    
      elseif($p->getItemInHand()->getId() == Item::CLOCK && $p->getExpLevel() >= 10) {
        if($this->getOwner()->playerclass->get($p->getName()) === "assassin" && $level->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
          if ($p->getFood () >= 4) {
            $effect = Effect::getEffect(14)->setDuration(60)->setAmplifier(1)->setVisible(true);
            $p->addEffect($effect);
            $x = $p->x;
            $y = $p->y;
            $z = $p->z;
            $level->addParticle(new LavaParticle(new Vector3($x, $y, $z), 5)); //Cloak of invisibility
            $p->setFood ($p->getFood () - 4);
            $p->sendPopup (TF::AQUA . "-4 Mana");
          }
        }
      } //Assassin Cloak
    
      elseif($p->getItemInHand()->getId() == Item::BONE && $p->getExpLevel() >= 10) {
        if($this->getOwner()->playerclass->get($p->getName()) === "mage" && $p->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
          if ($p->getFood () >= 7) {
            $effect = Effect::getEffect(10)->setDuration(100)->setAmplifier(1)->setVisible(true);
            $p->addEffect($effect);
            $x = $p->x;
            $y = $p->y;
            $z = $p->z;
            $level->addParticle(new HeartParticle(new Vector3($x, $y + 2, $z), 5)); //Bone of life
            $p->setFood ($p->getFood () - 7);
            $p->sendPopup (TF::AQUA . "-7 Mana");
          }
        }
      } //Mage bone
    
      elseif($p->getItemInHand()->getId() == Item::REDSTONE && $p->getExpLevel() >= 10) {
        if($this->getOwner()->playerclass->get($p->getName()) === "assassin" && $p->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld")) {
          if ($p->getFood () >= 6) {
            $effect = Effect::getEffect(5)->setDuration(200)->setAmplifier(1)->setVisible(true);
            $p->addEffect($effect);
            $x = $p->x;
            $y = $p->y;
            $z = $p->z;
            $level->addParticle(new EntityFlameParticle(new Vector3($x, $y+3, $z), 3)); //Rage powder
            $p->setFood ($p->getFood () - 6);
            $p->sendPopup (TF::AQUA . "-6 Mana");
          }
        }
      } //Warrior powder
    
      if($p->getItemInHand()->getId() == Item::STICK) {
        $p->sendPopup(TF:: AQUA . "Wand\n" . TF:: GRAY . "Fireball - Mage");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::FEATHER) {
        $p->sendPopup(TF:: AQUA . "Dagger\n" . TF::GRAY . "Stab - Assassin");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::BRICK) {
        if($p->getExpLevel() >= 8) {
          $p->sendPopup(TF:: AQUA . "Shield\n" . TF::GRAY . "Slam + Resistance - Warrior");
        } else {
          $p->sendPopup(TF:: AQUA . "Shield\n" . TF::GRAY . "Slam - Warrior");
        }
      } 
    
      elseif($p->getItemInHand()->getId() == Item::CLOCK) {
        $p->sendPopup(TF:: AQUA . "Cloak of Invisibility\n" . TF::GRAY . "Invisibility - Assassin");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::IRON_SWORD) {
        $p->sendPopup(TF:: AQUA . "Sword\n" . TF::GRAY . "Strike - Warrior");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::BONE) {
        $p->sendPopup(TF:: AQUA . "Bone of Life\n" . TF::GRAY . "Regeneration - Mage");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::REDSTONE) {
        $p->sendPopup(TF:: AQUA . "Rage Powder\n" . TF::GRAY . "Strength - Warrior");
      } 
    
      elseif($p->getItemInHand()->getId() == Item::BOOK) {
        $p->sendPopup(TF:: AQUA . "Abilities Book");
        if($this->getOwner()->playerclass->get($p->getName()) === "assassin") {
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
      } 
    
      elseif($p->getItemInHand()->getId() == Item::IRON_SHOVEL) {
       $p->sendPopup(TF:: AQUA . "Fissure Hammer\n" . TF:: GRAY . "Fissure - Warrior");
      }
    }
  }
  public function onCraft(CraftItemEvent $event) {
    if($event->getPlayer()->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld") && $this->getOwner()->config->get("DisableItemLosing") == true) {
      $event->setCancelled(); //denies any crafting, since that could get rid of important items
      }
  }
  
  public function onSmelt(FurnaceSmeltEvent $event) {
    if($event->getFurnace()->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld") && $this->getOwner()->config->get("DisableItemLosing") == true) {
      $event->setCancelled(); //same counts for smelting items in a furnace
    }
  }
  public function onBurn(FurnaceBurnEvent $event) {
    if($event->getFurnace()->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld") && $this->getOwner()->config->get("DisableItemLosing") == true) {
      $event->setCancelled(); //same counts for burning items in a furnace
      }
  }
  public function onDrop(PlayerDropItemEvent $event) {
    if($event->getPlayer()->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld") && $this->getOwner()->config->get("DisableItemLosing") == true) {
      $event->setCancelled();  //same counts for dropping items out of your inventory
    }
  }
  public function onDeath(PlayerDeathEvent $event) {
    if($event->getPlayer()->getLevel()->getFolderName() == $this->getOwner()->config->get("RPGworld") && $this->getOwner()->config->get("DisableItemLosing") == true) {
      $event->setKeepInventory(true); 
      $event->setKeepExperience(true);
    }
  }
  
  public function onExpChange(PlayerExperienceChangeEvent $event) {
    $p = $event->getPlayer();
    //if($p instanceof Player) {
      if($this->getOwner()->playerclass->get($p->getName()) === "mage" && $p->getFilledXp() >= 170) {
        $bone = Item::get(Item::BONE, 0, 1); 
        $p->getInventory()->addItem($bone); 
        $p->sendMessage($this->getOwner ()->config->get("LevelUpMessage"));
        $p->sendMessage(TF::GREEN . "You have unlocked the Regeneration spell!");
        
      } elseif($this->getOwner()->playerclass->get($p->getName()) === "assassin" && $p->getFilledXp() >= 170) {
        $clock = Item::get(Item::CLOCK, 0, 1); 
        $p->getInventory()->addItem($clock); 
        $p->sendMessage($this->getOwner ()->config->get("LevelUpMessage"));
        $p->sendMessage(TF::GREEN . "You have unlocked the Invisibility spell!");
        
      } elseif($this->getOwner()->playerclass->get($p->getName()) === "tanker" && $p->getFilledXp() >= 170) {
        $p->sendMessage($this->getOwner ()->config->get("LevelUpMessage"));
        $p->sendMessage(TF::GREEN . "You have unlocked the Resistance spell!");
        
      } elseif($this->getOwner()->playerclass->get($p->getName()) === "warrior" && $p->getFilledXp() >= 170) {
        $redstone = Item::get(Item::REDSTONE, 0, 1);
        $p->getInventory()->addItem($redstone);
        $p->sendMessage($this->getOwner ()->config->get("LevelUpMessage"));
        $p->sendMessage(TF::GREEN . "You have unlocked the strength spell!");
      }

    } //elseif($p instanceof Player && $event->getExp() >= 370) {
      //start for special weapons
    //} 
  //}
  
  public function onBreak(BlockBreakEvent $event) {
    $p = $event->getPlayer();
    if($p->getLevel()->getName() == $this->getOwner()->config->get("RPGworld")) {
      if($this->getOwner()->config->get("AllowBlockBreaking") == false) {
       if(!$p->hasPermission("rpg.break")) {
        $event->setCancelled();
       }
      }
    }
  }
  
  public function onPlace(BlockPlaceEvent $event) {
    $p = $event->getPlayer();
    if($p->getLevel()->getName() == $this->getOwner()->config->get("RPGworld")) {
      if($this->getOwner()->config->get("AllowBlockPlacing") == false) {
       if(!$p->hasPermission("rpg.place")) {
        $event->setCancelled();
       }
      }
    }
  }
  
  public function onLevelChange(EntityLevelChangeEvent $event) {
    $p = $event->getEntity();
    if($p instanceof Player) {
      $original = $event->getOrigin();
      $target = $event->getTarget();
      if($original->getName() == $this->getOwner()->config->get("RPGworld")) {
        $p->setMaxHealth($p->getXpLevel() * 0.20 + 20);
        $p->setHealth($p->getXpLevel() * 0.20 + 20);
      } elseif($original->getName() == $this->getOwner()->config->get("RPGworld")) {
        $p->setMaxHealth(20);
      }
    }
  }
}
