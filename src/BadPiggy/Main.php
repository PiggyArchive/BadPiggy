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
	public $holes = array();
	public $webs = array();
	public $infall;
	public $invoid;
	public $lavablock;
	public $freeze;
	public $babble;
	public $exblock;
	public $unaware;
	public $mute;
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

	public function infall(Player $player){
		$this->infall[strtolower($player->getName())] = true;
		$this->fall($player);
	}

	public function web(Player $player){
		$x1 = $player->x + 3;
		$x2 = $player->x - 1;
		$y1 = $player->y + 3;
		$y2 = $player->y - 1;
		$z1 = $player->z + 3;
		$z2 = $player->z - 1;
		for($x = $x2; $x < $x1; $x++){
			for($y = $y2; $y < $y1; $y++){
				for($z = $z2; $z < $z1; $z++){
					$vector3 = new Vector3($x, $y, $z);
					array_push($this->webs, array($player->getLevel(), $vector3, $player->getLevel()->getBlock($vector3)));
					$player->getLevel()->setBlock($vector3, Block::get(Block::COBWEB));
				}
			}
		}
	}

	public function void(Player $player){
		$player->teleport(new Vector3($player->x, 0, $player->z));
	}

	public function invoid(Player $player){
		$this->invoid[strtolower($player->getName())] = true;
		$this->void($player);
	}

	public function lavablock(Player $player){
		$this->lavablock[strtolower($player->getName())] = true;
	}

	public function hole(Player $player){
		$x = $player->x;
		$y1 = $player->y;
		$y2 = $player->y - 26;
		$z = $player->z;
		for($y = $y2; $y < $y1; $y++){
			$vector3 = new Vector3($x, $y, $z);
			array_push($this->holes, array($player->getLevel(), $vector3, $player->getLevel()->getBlock($vector3)));
			$player->getLevel()->setBlock($vector3, Block::get(Block::AIR));
		}
		$player->teleport(new Vector3(floor($player->x) + 0.5, floor($player->y), floor($player->z) + 0.5)); //Make sure player falls in ;)
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

	public function unaware(Player $player){
		foreach($this->getServer()->getOnlinePlayers() as $p){
			$player->hidePlayer($p);
		}
		$this->unaware[strtolower($player->getName())] = true;
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

	public function mute(Player $player){
		$this->mute[strtolower($player->getName())] = true;
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

	public function useless(Player $player){
		foreach($player->getInventory()->getContents() as $index => $item){
			$item->setCustomName("Useless");
			$player->getInventory()->setItem($index, $item);
		}
	}

	public function scream(Player $player){
		$player->getLevel()->addSound(new GhastShootSound($player));
	}

	public function end(Player $player){
		$player->kill();
		if(isset($this->infall[strtolower($player->getName())])){
			unset($this->infall[strtolower($player->getName())]);
		}
		if(isset($this->invoid[strtolower($player->getName())])){
			unset($this->invoid[strtolower($player->getName())]);
		}
		if(isset($this->lavablock[strtolower($player->getName())])){
			unset($this->lavablock[strtolower($player->getName())]);
		}
		if(isset($this->freeze[strtolower($player->getName())])){
			unset($this->freeze[strtolower($player->getName())]);
		}
		if(isset($this->babble[strtolower($player->getName())])){
			unset($this->babble[strtolower($player->getName())]);
		}
		if(isset($this->exblock[strtolower($player->getName())])){
			unset($this->exblock[strtolower($player->getName())]);
		}
		if(isset($this->unaware[strtolower($player->getName())])){
			foreach($this->getServer()->getOnlinePlayers() as $p){
				$player->showPlayer($p);
			}
			unset($this->unaware[strtolower($player->getName())]);
		}
		if(isset($this->mute[strtolower($player->getName())])){
			unset($this->mute[strtolower($player->getName())]);
		}
		if(isset($this->maim[strtolower($player->getName())])){
			unset($this->maim[strtolower($player->getName())]);
		}
	}

	public function restore(){
		foreach($this->holes as $info){
			$level = $info[0];
			$vector3 = $info[1];
			$block = $info[2];
			$level->setBlock($vector3, $block);
		}
		foreach($this->webs as $info){
			$level = $info[0];
			$vector3 = $info[1];
			$block = $info[2];
			$level->setBlock($vector3, $block);
		}
		$this->holes = array();
		$this->webs = array();
	}

}