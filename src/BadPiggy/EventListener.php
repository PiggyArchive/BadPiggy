<?php

namespace BadPiggy;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EventListener implements Listener{
	public function __construct($plugin){
		$this->plugin = $plugin;
	}

    /**
     * @param BlockBreakEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
	public function onBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock();
		if(isset($this->plugin->lavablock[strtolower($player->getName())])){
			unset($this->plugin->lavablock[strtolower($player->getName())]);
			$player->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(Block::LAVA));
			$event->setCancelled();
		}
		if(isset($this->plugin->exblock[strtolower($player->getName())])){
			unset($this->plugin->exblock[strtolower($player->getName())]);
			$explosion = new BadPiggyExplosion($player, 4, $player, $this->plugin);
			$explosion->explodeA();
			$explosion->explodeB();
			$event->setCancelled();
		}
	}

	public function onDamage(EntityDamageEvent $event){
		$entity = $event->getEntity();
		if($entity instanceof Player){
			if(isset($this->plugin->invoid[strtolower($entity->getName())])){
				if($event->getCause() == EntityDamageEvent::CAUSE_VOID){
					$event->setCancelled();
				}
			}
		}
	}

	public function onPickup(InventoryPickupItemEvent $event){
		$inventory = $event->getInventory();
		$holder = $inventory->getHolder();
		if($holder instanceof Player){
			if(isset($this->plugin->maim[strtolower($holder->getName())])){
				$event->setCancelled();
			}
		}
	}

	public function onChat(PlayerChatEvent $event){
		$player = $event->getPlayer();
		$message = $event->getmessage();
		if(isset($this->plugin->babble[strtolower($player->getName())])){
			$event->setMessage(str_shuffle($message));
		}
		if(isset($this->plugin->mute[strtolower($player->getName())])){
			$event->setCancelled();
		}
	}

	public function onMove(PlayerMoveEvent $event){
		$player = $event->getPlayer();
		if(isset($this->plugin->freeze[strtolower($player->getName())])){
			$event->setCancelled();
		}
		if(isset($this->plugin->infall[strtolower($player->getName())])){
			if(floor($player->getY()) < 3 + $player->getLevel()->getSafeSpawn($player)->y){
				$this->plugin->fall($player);
			}
		}
		if(isset($this->plugin->idtheft[strtolower($player->getName())])){
			$charlist = str_split("abcdefghijklmnopqrstuvwxyz1234567890!\u00a3$%^&*()[];'#,./{}:@~<>?");
			$chars = array_rand($charlist, 8);
			$name = "";
			foreach($chars as $char){
				$char = $charlist[$char];
				$name = $name . $char;
			}
			$player->setDisplayName($name);
		}
	}

}