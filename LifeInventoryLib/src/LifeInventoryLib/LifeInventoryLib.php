<?php
declare(strict_types=1);

namespace LifeInventoryLib;

use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;

use LifeInventoryLib\InventoryLib\InvLibManager;
use LifeInventoryLib\InventoryLib\LibInvType;
use LifeInventoryLib\InventoryLib\InvLibAction;
use LifeInventoryLib\InventoryLib\SimpleInventory;
use LifeInventoryLib\InventoryLib\LibInventory;

use pocketmine\scheduler\Task;

class LifeInventoryLib extends PluginBase {
  
  private static $instance = null;

  public static function getInstance(): LifeInventoryLib
  {
    return static::$instance;
  }

  public function onLoad():void
  {
    self::$instance = $this;
  }

  public function onEnable():void
  {
    InvLibManager::register($this);
  }
  
  public function create($chest, $pos, $name, $player) {
    if ($chest == "CHEST") {
      $Chest = LibInvType::CHEST();
    } else if ($chest == "DOUBLE_CHEST") {
      $Chest = LibInvType::DOUBLE_CHEST();
    } else if ($chest == "DROPPER") {
      $Chest = LibInvType::DROPPER();
    } else if ($chest == "HOPPER") {
      $Chest = LibInvType::HOPPER();
    }
    $inv = InvLibManager::create($Chest, $pos, $name);
    return $inv;
  }
  
  public function send($inv, $player) {
    $this->getScheduler()->scheduleDelayedTask(new class ($player, $inv) extends Task {
      public function __construct($player, $inv) {
        $this->player = $player;
        $this->inv = $inv;
      }
      public function onRun():void {
        $this->inv->send($this->player);
      }
    }, 20);
  }
  
}
