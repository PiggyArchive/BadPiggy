<?php

namespace BadPiggy;

use BadPiggy\Commands\BreakReplaceCommand;
use pocketmine\level\Explosion;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase{
	public function onEnable(){
    	$this->getServer()->getCommandMap()->register('badpiggy', new BadPiggyCommand('badpiggy', $this));
		$this->getLogger()->info("Enabled!");
	}

	public function fall(Player $player){
		$player->teleport($player->x, $player->y + 200, $player->z);
	}

	public function explode(Player $player){
		$explosion = new Explosion($player, 4, $player);
		$explosion->explodeA();
	}

	public function burn(Player $player, $time){
		$player->setOnFire($time);
	}

	public function void(Player $player){
		$player->teleport($player->x, 0, $player->z);
	}

	public function fexplode(Player $player){
		$explosion = new Explosion($player, 4, $player);
		$explosion->explodeB();
	}

	public function glass(Player $player){
		$player->getLevel()->setBlock(new Vector3($player->x, 128, $player->z), Block::get(Block::GLASS));
		$player->teleport(new Vector3($player->x, 128, $player->z));
	}

	public function spam(Player $player){
		for($i = 0; $i < 50; $i++){
			$player->sendMessage("CHECK OUT MCPEPIG's PLUGINS! THEY'RE AWESOME!!!");
		}
	}

	public function pumpkin(Player $player){
		$player->getInventory()->setHelmet(Item::get(Item::PUMPKIN));
	}

}