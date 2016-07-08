<?php

namespace BadPiggy;

use BadPiggy\Commands\BadPiggyCommand;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase{
	public $invoid;
	public $lavablock;
	public $freeze;
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
		$explosion->explodeB();
	}

	public function strike(Player $player){
		$pk = new AddEntityPacket();
        $pk->type = 93;
        $pk->eid = Entity::$entityCount++;
        $pk->x = $player->x;
       	$pk->y = $player->y;
        $pk->z = $player->z;        
        $pk->speedX = 0;
        $pk->speedY = 0;
        $pk->speedZ = 0;
        $pk->yaw = 0;
        $pk->pitch = 0;
        Server::broadcastPacket($this->getServer()->getOnlinePlayers(), $pk);
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

	public function freeze(Player $player){
		$this->freeze[strtolower($player->getName())] = true;
	}

	public function fexplode(Player $player){
		$explosion = new Explosion($player, 4, $player);
		$explosion->explodeB();
	}

	public function strip(Player $player){
		$player->getInventory()->clearAll();
	}

	public function glass(Player $player){
		$Vector3 = new Vector3($player->x, 125, $player->z);
		$player->getLevel()->setBlock($Vector3, Block::get(Block::GLASS));
		$player->teleport(new Vector3($player->x, 126, $player->z));
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
			$player->sendMessage("§aCHECK OUT MCPEPIG's PLUGINS! THEY'RE AWESOME!!!");
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
		$player->getLevel()->addSound(new GhastShootSound($player));
	}

	public function end(Player $player){
		$player->kill();
		if(isset($this->invoid[strtolower($player->getName())])){
			unset($this->invoid[strtolower($player->getName())]);
		}
		if(isset($this->freeze[strtolower($player->getName())])){
			unset($this->freeze[strtolower($player->getName())]);
		}
		if(isset($this->babble[strtolower($player->getName())])){
			unset($this->babble[strtolower($player->getName())]);
		}
		if(isset($this->maim[strtolower($player->getName())])){
			unset($this->maim[strtolower($player->getName())]);
		}
	}

}