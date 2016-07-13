<?php

namespace BadPiggy;

use BadPiggy\Commands\BadPiggyCommand;
use BadPiggy\Sounds\TNTPrimeSound;

use pocketmine\block\Block;
use pocketmine\command\CommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\Entity;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\item\Item;
use pocketmine\level\sound\GhastSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\protocol\AddEntityPacket;
use pocketmine\network\protocol\SetTimePacket;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase{
	public $explode = array();
	public $holes = array();
	public $webs = array();
	public $infall;
	public $invoid;
	public $lavablock;
	public $freeze;
	public $babble;
	public $exblock;
	public $blind;
	public $drunk;
	public $starve;
	public $slow;
	public $poison;
	public $unaware;
	public $rewind;
	public $unrewind;
	public $mute;
	public $spam;
	public $maim;
	public $brittle;
	public $idtheft;
	public $rename;

	public function onEnable(){
		$this->getServer()->getCommandMap()->register('badpiggy', new BadPiggyCommand('badpiggy', $this));
		$this->getServer()->getScheduler()->scheduleRepeatingTask(new BadPiggyTick($this), 1);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->getLogger()->info("§aEnabled.");
	}

	public function fall(Player $player){
		$player->teleport($player->add(0, 200));
	}

	public function explode(Player $player){
		$explosion = new BadPiggyExplosion($player, 4, $player, $this);
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
		$explosion = new BadPiggyExplosion($player, 4, $player, $this);
		$explosion->explodeB();
	}

	public function blind(Player $player){
		$effect = Effect::getEffect(15);
		$effect->setDuration(999999);
		$effect->setAmplifier(2);
		$effect->setVisible(false);
		$player->addEffect($effect);
		$this->blind[strtolower($player->getName())] = true;
	}

	public function drunk(Player $player){
		$effect = Effect::getEffect(9);
		$effect->setDuration(999999);
		$effect->setAmplifier(2);
		$effect->setVisible(false);
		$player->addEffect($effect);
		$this->drunk[strtolower($player->getName())] = true;
	}

	public function starve(Player $player){
		$player->setFood(0);
		$effect = Effect::getEffect(17);
		$effect->setDuration(999999);
		$effect->setAmplifier(2);
		$effect->setVisible(false);
		$player->addEffect($effect);	
		$this->starve[strtolower($player->getName())] = true;	
	}

	public function slow(Player $player){
 		$player->getAttributeMap()->getAttribute(5)->setValue(0.05);
 		$this->slow[strtolower($player->getName())] = true;	
	}


	public function poison(Player $player){
		$effect = Effect::getEffect(19);
		$effect->setDuration(999999);
		$effect->setAmplifier(2);
		$effect->setVisible(false);
		$player->addEffect($effect);	
		$this->poison[strtolower($player->getName())] = true;	
	}

	public function strip(Player $player){
		$player->getInventory()->clearAll();
	}

	public function glass(Player $player){
		$vector3 = new Vector3($player->x, 125, $player->z);
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

	public function night(Player $player){
		$pk = new SetTimePacket();
		$pk->time = 18000;
		$pk->started = false;
		$player->dataPacket($pk);
	}

	public function rewind(Player $player){
		$this->rewind[strtolower($player->getName())] = $player->getLevel()->getTime();
		$this->unrewind[strtolower($player->getName())] = 0;
	}

	public function exblock(Player $player){
		$this->exblock[strtolower($player->getName())] = true;
	}

	public function mute(Player $player){
		$this->mute[strtolower($player->getName())] = true;
	}

	public function spam(Player $player){
		$this->spam[strtolower($player->getName())] = true;
	}

	public function fakeop(Player $player){
		$player->sendMessage("§7You are now op!");
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

	public function brittle(Player $player){
		$this->brittle[strtolower($player->getName())] = true;
	}

	public function tnttrick(Player $player){
		$player->getLevel()->setBlock($player->add(1, 1), Block::get(Block::TNT));
		$player->getLevel()->setBlock($player->subtract(1)->add(0, 1), Block::get(Block::TNT));
		$player->getLevel()->setBlock($player->add(0, 1, 1), Block::get(Block::TNT));
		$player->getLevel()->setBlock($player->subtract(0, 0, 1)->add(0, 1), Block::get(Block::TNT));
		$player->getLevel()->addSound(new TNTPrimeSound($player));
	}

	public function squid(Player $player){
		$chunk = $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", floor($player->x)),
				new DoubleTag("", floor($player->y) + 5),
				new DoubleTag("", floor($player->z))
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", lcg_value() * 360),
				new FloatTag("", 0)
			]),
		]);
		$entity = Entity::createEntity("Squid", $chunk, $nbt);
		$entity->spawnToAll();
	}

	public function crash(Player $player){
		$chunk = $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4);
		$nbt = new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", floor($player->x)),
				new DoubleTag("", floor($player->y) + 5),
				new DoubleTag("", floor($player->z))
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", lcg_value() * 360),
				new FloatTag("", 0)
			]),
		]);
		$entity = Entity::createEntity("Villager", $chunk, $nbt);
		$entity->setDataProperty(16, Entity::DATA_TYPE_BYTE, 5);
		$entity->spawnTo($player);		
	}

	public function useless(Player $player){
		foreach($player->getInventory()->getContents() as $index => $item){
			$item->setCustomName("Useless");
			$player->getInventory()->setItem($index, $item);
		}
	}

	public function idtheft(Player $player){
		$this->idtheft[strtolower($player->getName())] = true;
	}

	public function scream(Player $player){
		$player->getLevel()->addSound(new GhastSound($player));
	}

	public function chat(Player $player, $message){
		$this->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, $message));
		if(!$ev->isCancelled()){
			$this->getServer()->broadcastMessage($this->getServer()->getLanguage()->translateString($ev->getFormat(), [$ev->getPlayer()->getDisplayName(), $ev->getMessage()]), $ev->getRecipients());
		}
	}

	public function rename(Player $player, $name){
		$this->rename[strtolower($player->getName())] = true;
		$player->setDisplayName($name);
	}

	public function kick(Player $player, $reason){
		$player->close("", $reason);
	}

	public function end(Player $player){
		$player->setHealth(0);
		$this->stop($player);
	}

	public function stop(Player $player){
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
		if(isset($this->blind[strtolower($player->getName())])){
			unset($this->blind[strtolower($player->getName())]);
		}
		if(isset($this->drunk[strtolower($player->getName())])){
			unset($this->drunk[strtolower($player->getName())]);
		}
		if(isset($this->starve[strtolower($player->getName())])){
			unset($this->starve[strtolower($player->getName())]);
		}
		if(isset($this->slow[strtolower($player->getName())])){
			$player->getAttributeMap()->getAttribute(5)->setValue(0.1);
			unset($this->slow[strtolower($player->getName())]);
		}
		if(isset($this->poison[strtolower($player->getName())])){
			unset($this->poison[strtolower($player->getName())]);
		}
		if(isset($this->unaware[strtolower($player->getName())])){
			foreach($this->getServer()->getOnlinePlayers() as $p){
				$player->showPlayer($p);
			}
			unset($this->unaware[strtolower($player->getName())]);
		}
		if(isset($this->rewind[strtolower($player->getName())])){
			unset($this->rewind[strtolower($player->getName())]);
		}
		if(isset($this->unrewind[strtolower($player->getName())])){
			unset($this->unrewind[strtolower($player->getName())]);
		}
		if(isset($this->mute[strtolower($player->getName())])){
			unset($this->mute[strtolower($player->getName())]);
		}
		if(isset($this->spam[strtolower($player->getName())])){
			unset($this->spam[strtolower($player->getName())]);
		}
		if(isset($this->maim[strtolower($player->getName())])){
			unset($this->maim[strtolower($player->getName())]);
		}
		if(isset($this->idtheft[strtolower($player->getName())])){
			$player->setDisplayName($player->getName());
			unset($this->idtheft[strtolower($player->getName())]);
		}
		if(isset($this->rename[strtolower($player->getName())])){
			$player->setDisplayName($player->getName());
			unset($this->rename[strtolower($player->getName())]);
		}
	}

	public function restore(CommandSender $sender){
		$count = 0;
		foreach($this->explode as $info){
			$count++;
			$level = $info[0];
			$vector3 = $info[1];
			$block = $info[2];
			$level->setBlock($vector3, $block);
		}
		foreach($this->holes as $info){
			$count++;
			$level = $info[0];
			$vector3 = $info[1];
			$block = $info[2];
			$level->setBlock($vector3, $block);
		}
		foreach($this->webs as $info){
			$count++;
			$level = $info[0];
			$vector3 = $info[1];
			$block = $info[2];
			$level->setBlock($vector3, $block);
		}
		$this->explode = array();
		$this->holes = array();
		$this->webs = array();
		$sender->sendMessage("§a" . $count . " blocks restored.");
	}

}