<?php

namespace BadPiggy;

use BadPiggy\Commands\BadPiggyCommand;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\sound\GhastSound;
use pocketmine\math\Vector3;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;

class Main extends PluginBase{
	public $invoid;
	public $lavablock;
	public $babble;
	public $exblock;
	public $maim;

	public function onEnable(){
    	$this->getServer()->getCommandMap()->register('badpiggy', new BadPiggyCommand('badpiggy', $this));
    	$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getLogger()->info("§aEnabled.");
	}

	public function fall(Player $player){
		$player->teleport($player->add(0, 200));
	}

	public function explode(Player $player){
		$explosion = new Explosion($player, 4);
		$explosion->explodeA();
	}

	public function burn(Player $player, $time){
		$player->setOnFire($time);
	}

	public function void(Player $player){
		$player->teleport(new Vector3($player->x, 0, $player->z));
	}

	public function invoid(Player $player){
		$this->invoid[strtolower($player->getName())] = true;
		$player->teleport(new Vector3($player->x, 0, $player->z));
	}

	public function lavablock(Player $player){
		$this->lavablock[strtolower($player->getName())] = true;
	}

	public function fexplode(Player $player){
		$explosion = new Explosion($player, 4, $player);
		$explosion->explodeB();
	}

	public function glass(Player $player){
		$player->getLevel()->setBlock(new Vector3($player->x, 128, $player->z), Block::get(Block::GLASS));
		$player->teleport(new Vector3($player->x, 128, $player->z));
	}

	public function babble(Player $player){
		$this->babble[strtolower($player->getName())] = true;
	}

	public function leveldown(Player $player){
		switch($this->getServer()->getName()){
			case "ClearSky":
			case "ImagicalMine":
			case "Genisys":
				$player->setExp(0);
				break;
		}
	}

	public function exblock(Player $player){
		$this->exblock[strtolower($player->getName())] = true;
	}

	public function spam(Player $player){
		for($i = 0; $i < 50; $i++){
			$player->sendMessage("CHECK OUT MCPEPIG's PLUGINS! THEY'RE AWESOME!!!");
		}
	}

	public function popular(Player $player){
		foreach($this->getServer()->getOnlinePlayers() as $p){
			$p->teleport($player);
		}
	}

	public function pumpkin(Player $player){
		$player->getInventory()->setHelmet(Item::get(Item::PUMPKIN));
	}

	public function armour(Player $player){
		$player->getInventory()->setHelmet(Item::get(Item::BUCKET));
		$player->getInventory()->setChestplate(Item::get(Item::BUCKET));
		$player->getInventory()->setLeggings(Item::get(Item::BUCKET));
		$player->getInventory()->setBoots(Item::get(Item::BUCKET));
	}

	public function maim(Player $player){
		$this->maim[strtolower($player->getName())] = true;
	}

	public function scream(Player $player){
		$players->getLevel()->addSound(new GhastSound($player), array($player));
	}

	public function end(Player $player){
		$player->kill();
		if(isset($this->invoid[strtolower($player->getName())])){
			unset($this->invoid[strtolower($player->getName())]);
		}
		if(isset($this->babble[strtolower($player->getName())])){
			unset($this->babble[strtolower($player->getName())]);
		}
		if(isset($this->maim[strtolower($player->getName())])){
			unset($this->maim[strtolower($player->getName())]);
		}
	}

}