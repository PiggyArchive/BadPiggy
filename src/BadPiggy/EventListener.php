<?php

namespace BadPiggy;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\math\Vector3;

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
			$player->getLevel()->setBlock($block->getPosition(), Block::get(Block::LAVA));
			$event->setCancelled();
		}
		if(isset($this->plugin->exblock[strtolower($player->getName())])){
			unset($this->plugin->exblock[strtolower($player->getName())]);
			$explosion = new Explosion($player, 4, $player);
			$explosion->explodeA();
			$event->setCancelled();
		}
	}

}