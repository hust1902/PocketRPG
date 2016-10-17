<?php
namespace PocketRPG\tasks;
use PocketRPG\eventlistener\EventListener;
use pocketmine\scheduler\PluginTask;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\level\particle\FlameParticle;
use pocketmine\math\Vector3;
class FireCageTask extends PluginTask {
    public $plugin;
    public $seconds;
      public function __construct(EventListener $plugin, $pos, Player $p) {
          parent::__construct($plugin->getOwner());
          $this->pos = $pos;
          $this->plugin = $plugin;
          $this->seconds = 0;
          $this->p = $p;
      }
      public function getPlugin() {
          return $this->plugin->getOwner();
      }
      public function onRun($tick) {
      for($k = 0; $k < 7; $k++) {
        $this->p->teleport($this->pos, $this->p->yaw, $this->p->pitch);
        $this->p->getLevel()->addParticle(new FlameParticle(new Vector3($this->pos->x + 1, $this->pos->y + $k, $this->pos->z)));
        $this->p->getLevel()->addParticle(new FlameParticle(new Vector3($this->pos->x, $this->pos->y + $k, $this->pos->z + 1)));
        $this->p->getLevel()->addParticle(new FlameParticle(new Vector3($this->pos->x, $this->pos->y + $k, $this->pos->z - 1)));
        $this->p->getLevel()->addParticle(new FlameParticle(new Vector3($this->pos->x - 1, $this->pos->y + $k, $this->pos->z)));
        }
      if($this->seconds === 10) {
              unset($this->tasks[$this->getTaskId()]);
              $this->getPlugin()->getServer()->getScheduler()->cancelTask($this->getTaskId());
      }
          $this->seconds++;
      }
}
