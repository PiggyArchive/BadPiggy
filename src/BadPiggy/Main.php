<?php

namespace BadPiggy;

use BadPiggy\Commands\BreakReplaceCommand;
use pocketmine\level\Explosion;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase{
	public $infall = array(); //Coming Soon

	public function onEnable(){
    	$this->getServer()->getCommandMap()->register('badpiggy', new BadPiggyCommand('badpiggy', $this));
		$this->getLogger()->info("Enabled!");
	}

	public function fall(Player $player){
		$player->teleport($player->x, $player->y + 200, $player->z);
	}

	public function explode(Player $player){
		$explosion = new Explosion($player, 4, $player);
		$explosion->explodeB();
	}

	public function strike(Player $player){ //Coming Soon

	}

	public function burn(Player $player, $time){
		$player->setOnFire($time);
	}

	public function infall(Player $player){ //Coming Soon
		$this->infall[strtolower($player->getName())] = true;
	}

	public function web(Player $player){ //Coming Soon

	}

	public function end(){ //Coming Soon
		$player->kill();
		if(isset($this->infall[strtolower($player->getName())])){
			unset($this->infall[strtolower($player->getName())]);
		}
	}

}