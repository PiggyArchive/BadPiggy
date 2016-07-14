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

class Main extends PluginBase {
    public $explode = array();
    public $holes = array();
    public $webs = array();
    public $display = array();
    public $infall;
    public $invoid;
    public $lavablock;
    public $freeze;
    public $babble;
    public $spin;
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
    public $potate;
    public $rename;

    public function onEnable() {
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register('badpiggy', new BadPiggyCommand('badpiggy', $this));
        $this->getServer()->getScheduler()->scheduleRepeatingTask(new BadPiggyTick($this), 1);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getLogger()->info("§aEnabled.");
    }

    public function fall(Player $player) {
        $player->teleport($player->add(0, 200), $player->yaw, $player->pitch);
    }

    public function explode(Player $player) {
        $explosion = new BadPiggyExplosion($player, 4, $player, $this);
        $explosion->explodeA();
        $explosion->explodeB();
    }

    public function strike(Player $player) {
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

    public function burn(Player $player, $time) {
        $player->setOnFire($time);
    }

    public function infall(Player $player) {
        $this->infall[strtolower($player->getName())] = true;
        $this->fall($player);
    }

    public function web(Player $player) {
        $x1 = $player->x + 3;
        $x2 = $player->x - 1;
        $y1 = $player->y + 3;
        $y2 = $player->y - 1;
        $z1 = $player->z + 3;
        $z2 = $player->z - 1;
        for($x = $x2; $x < $x1; $x++) {
            for($y = $y2; $y < $y1; $y++) {
                for($z = $z2; $z < $z1; $z++) {
                    $vector3 = new Vector3($x, $y, $z);
                    array_push($this->webs, array(
                        $player->getLevel(),
                        $vector3,
                        $player->getLevel()->getBlock($vector3)));
                    $player->getLevel()->setBlock($vector3, Block::get(Block::COBWEB));
                }
            }
        }
    }

    public function void(Player $player) {
        $player->teleport(new Vector3($player->x, 0, $player->z), $player->yaw, $player->pitch);
    }

    public function invoid(Player $player) {
        $this->invoid[strtolower($player->getName())] = true;
        $this->void($player);
    }

    public function lavablock(Player $player) {
        $this->lavablock[strtolower($player->getName())] = true;
    }

    public function hole(Player $player) {
        $x = $player->x;
        $y1 = $player->y;
        $y2 = $player->y - 26;
        $z = $player->z;
        for($y = $y2; $y < $y1; $y++) {
            $vector3 = new Vector3($x, $y, $z);
            array_push($this->holes, array(
                $player->getLevel(),
                $vector3,
                $player->getLevel()->getBlock($vector3)));
            $player->getLevel()->setBlock($vector3, Block::get(Block::AIR));
        }
        $player->teleport($player->floor()->add(0.5, 0, 0.5), $player->yaw, $player->pitch);
    }

    public function teleport(Player $player) {
        $radius = $this->getConfig()->get("teleport-radius");
        $x1 = $player->x - $radius;
        $x2 = $player->x + $radius;
        $z1 = $player->z - $radius;
        $z2 = $player->z + $radius;
        $player->teleport($player->getLevel()->getSafeSpawn(new Vector3(mt_rand($x1, $x2), $player->y, mt_rand($z1, $z2))), $player->yaw, $player->pitch);
    }

    public function freeze(Player $player) {
        $this->freeze[strtolower($player->getName())] = true;
    }

    public function fexplode(Player $player) {
        $explosion = new BadPiggyExplosion($player, 4, $player, $this);
        $explosion->explodeB();
    }

    public function blind(Player $player) {
        $effect = Effect::getEffect(15);
        $effect->setDuration(999999);
        $effect->setAmplifier(2);
        $effect->setVisible(false);
        $player->addEffect($effect);
        $this->blind[strtolower($player->getName())] = true;
    }

    public function drunk(Player $player) {
        $effect = Effect::getEffect(9);
        $effect->setDuration(999999);
        $effect->setAmplifier(2);
        $effect->setVisible(false);
        $player->addEffect($effect);
        $this->drunk[strtolower($player->getName())] = true;
    }

    public function starve(Player $player) {
        $player->setFood(0);
        $effect = Effect::getEffect(17);
        $effect->setDuration(999999);
        $effect->setAmplifier(2);
        $effect->setVisible(false);
        $player->addEffect($effect);
        $this->starve[strtolower($player->getName())] = true;
    }

    public function slow(Player $player) {
        $player->getAttributeMap()->getAttribute(5)->setValue(0.05);
        $this->slow[strtolower($player->getName())] = true;
    }

    public function poison(Player $player) {
        $effect = Effect::getEffect(19);
        $effect->setDuration(999999);
        $effect->setAmplifier(2);
        $effect->setVisible(false);
        $player->addEffect($effect);
        $this->poison[strtolower($player->getName())] = true;
    }

    public function strip(Player $player) {
        $player->getInventory()->clearAll();
    }

    public function glass(Player $player) {
        $vector3 = new Vector3($player->x, 125, $player->z);
        $player->getLevel()->setBlock($vector3, Block::get(Block::GLASS));
        $player->teleport(new Vector3($player->x, 126, $player->z), $player->yaw, $player->pitch);
    }

    public function shoot(Player $player) {
        $vector3 = $player;
        switch($this->getStrDirection($player)) {
            case "North":
                $vector3 = $vector3->add(0, 0, 2);
                break;
            case "Northeast":
                $vector3 = $vector3->add(2, 0, 2);
                break;
            case "Northwest":
                $vector3 = $vector3->add(0, 0, 2)->subtract(2);
                break;
            case "South":
                $vector3 = $vector3->subtract(0, 0, 2);
                break;
            case "Southeast":
                $vector3 = $vector3->add(2)->subtract(0, 0, 2);
                break;
            case "Southwest":
                $vector3 = $vector3->subtract(2, 0, 2);
                break;
            case "West":
                $vector3 = $vector3->subtract(2);
                break;
            case "East":
                $vector3 = $vector3->add(2);
                break;
        }
        $motion = $player->subtract($vector3)->normalize()->multiply(5);
        $motion->y = ($motion->y + 5) * 2;
        $player->setMotion($motion);
    }

    public function anvil(Player $player) {
        $player->teleport($player->floor()->add(0.5, 0, 0.5), $player->yaw, $player->pitch);
        $player->getLevel()->setBlock($player->add(0, 15), Block::get(145));
    }

    public function babble(Player $player) {
        $this->babble[strtolower($player->getName())] = true;
    }

    public function spin(Player $player) {
        $this->spin[strtolower($player->getName())] = true;
    }

    public function unaware(Player $player) {
        foreach($this->getServer()->getOnlinePlayers() as $p) {
            $player->hidePlayer($p);
        }
        $this->unaware[strtolower($player->getName())] = true;
    }

    public function leveldown(Player $player) {
        switch($this->getServer()->getName()) {
            case "ClearSky":
                $player->setExperience(0);
                return true;
            case "ImagicalMine":
            case "Genisys":
                $player->setExp(0);
                return true;
        }
        return false;
    }

    public function flamingarrow(Player $player) {
        $chunk = $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4);
        $nbt = new CompoundTag("", ["Pos" => new ListTag("Pos", [new DoubleTag("", floor($player->x) + 0.5), new DoubleTag("", floor($player->y) + 10), new DoubleTag("", floor($player->z) + 0.5)]), "Motion" => new ListTag("Motion", [new DoubleTag("", 0), new DoubleTag("", 0), new DoubleTag("", 0)]), "Rotation" => new ListTag("Rotation", [new FloatTag("", lcg_value() * 360), new FloatTag("", 0)]), ]);
        $entity = Entity::createEntity("Arrow", $chunk, $nbt);
        $entity->setOnFire(999);
        $entity->spawnToAll();
    }

    public function night(Player $player) {
        $pk = new SetTimePacket();
        $pk->time = 18000;
        $pk->started = false;
        $player->dataPacket($pk);
    }

    public function rewind(Player $player) {
        $this->rewind[strtolower($player->getName())] = $player->getLevel()->getTime();
        $this->unrewind[strtolower($player->getName())] = 0;
    }

    public function slap(Player $player) {
        $vector3 = $player;
        switch($this->getStrDirection($player)) {
            case "North":
                $vector3 = $vector3->subtract(2, 0.5);
                break;
            case "Northeast":
                $vector3 = $vector3->subtract(2, 0.5, 2);
                break;
            case "Northwest":
                $vector3 = $vector3->add(0, 0, 2)->subtract(2, 0.5);
                break;
            case "South":
                $vector3 = $vector3->add(2)->subtract(0, 0.5);
                break;
            case "Southeast":
                $vector3 = $vector3->add(2)->subtract(0, 0.5, 2);
                break;
            case "Southwest":
                $vector3 = $vector3->add(2, 0, 2)->subtract(0, 0.5);
                break;
            case "West":
                $vector3 = $vector3->add(0, 0, 2)->subtract(0, 0.5);
                break;
            case "East":
                $vector3 = $vector3->subtract(0, 0.5, 2);
                break;
        }
        $motion = $player->subtract($vector3);
        $player->setMotion($motion);
    }

    public function exblock(Player $player) {
        $this->exblock[strtolower($player->getName())] = true;
    }

    public function mute(Player $player) {
        $this->mute[strtolower($player->getName())] = true;
    }

    public function spam(Player $player) {
        $this->spam[strtolower($player->getName())] = true;
    }

    public function cage(Player $player) {
        $player->getLevel()->setBlock($player->add(1, 15), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 16)->subtract(1), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 17)->subtract(0, 0, 1), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 18, 1), Block::get(145));
        $player->getLevel()->setBlock($player->add(1, 19), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 20)->subtract(1), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 21)->subtract(0, 0, 1), Block::get(145));
        $player->getLevel()->setBlock($player->add(0, 22, 1), Block::get(145));
        $player->teleport($player->floor()->add(0.5, 0, 0.5), $player->yaw, $player->pitch);
    }

    public function fakeop(Player $player) {
        $player->sendMessage("§7You are now op!");
    }

    public function popup(Player $player) {
        $items = $player->getInventory()->getContents();
        $item = array_rand($items, 1);
        $item = $items[$item];
        $player->getInventory()->removeItem($item);
        $motion = $player->getDirectionVector()->multiply(0.4);
        $player->getLevel()->dropItem($player->add(0, 1.3, 0), $item, $motion, 40);
    }

    public function popular(Player $player) {
        foreach($this->getServer()->getOnlinePlayers() as $p) {
            $p->teleport($player);
        }
    }

    public function display(Player $player) {
        for($i = 0; $i < 4; $i++) {
            array_push($this->display, $player->add(1, $i, 0));
            $player->getLevel()->setBlock($player->add(1, $i, 0), Block::get(Block::GLASS));
            array_push($this->display, $player->add(0, $i)->subtract(1));
            $player->getLevel()->setBlock($player->add(0, $i)->subtract(1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(0, $i, 1));
            $player->getLevel()->setBlock($player->add(0, $i, 1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(0, $i)->subtract(0, 0, 1));
            $player->getLevel()->setBlock($player->add(0, $i)->subtract(0, 0, 1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(1, $i, 1));
            $player->getLevel()->setBlock($player->add(1, $i, 1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(1, $i)->subtract(0, 0, 1));
            $player->getLevel()->setBlock($player->add(1, $i)->subtract(0, 0, 1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(0, $i, 1)->subtract(1));
            $player->getLevel()->setBlock($player->add(0, $i, 1)->subtract(1), Block::get(Block::GLASS));
            array_push($this->display, $player->add(0, $i)->subtract(1, 0, 1));
            $player->getLevel()->setBlock($player->add(0, $i)->subtract(1, 0, 1), Block::get(Block::GLASS));
        }
        array_push($this->display, $player->add(0, 3));
        $player->getLevel()->setBlock($player->add(0, 3), Block::get(Block::GLASS));
        array_push($this->display, $player->add(0, 1));
        $player->getLevel()->setBlock($player, Block::get(Block::GLASS));
        $player->teleport($player->floor()->add(0.5, 1, 0.5), $player->yaw, $player->pitch);
    }

    public function pumpkin(Player $player) {
        $player->getInventory()->setHelmet(Item::get(Item::PUMPKIN));
    }

    public function armour(Player $player) {
        $player->getInventory()->setHelmet(Item::get(Item::BUCKET));
        $player->getInventory()->setChestplate(Item::get(Item::BUCKET));
        $player->getInventory()->setLeggings(Item::get(Item::BUCKET));
        $player->getInventory()->setBoots(Item::get(Item::BUCKET));
    }

    public function tree(Player $player) {
        $bonemeal = Item::get(Item::DYE, 15);
        $player->getLevel()->setBlock($player->add(1), Block::get(Block::SAPLING, 2));
        $player->getLevel()->setBlock($player->subtract(1), Block::get(Block::SAPLING, 2));
        $player->getLevel()->setBlock($player->add(0, 0, 1), Block::get(Block::SAPLING, 2));
        $player->getLevel()->setBlock($player->subtract(0, 0, 1), Block::get(Block::SAPLING, 2));
        $player->getLevel()->getBlock($player->add(1))->onActivate($bonemeal, $player);
        $player->getLevel()->getBlock($player->subtract(1))->onActivate($bonemeal, $player);
        $player->getLevel()->getBlock($player->add(0, 0, 1))->onActivate($bonemeal, $player);
        $player->getLevel()->getBlock($player->subtract(0, 0, 1))->onActivate($bonemeal, $player);
        $player->teleport($player->floor()->add(0.5, 0, 0.5), $player->yaw, $player->pitch);
    }

    public function maim(Player $player) {
        $this->maim[strtolower($player->getName())] = true;
    }

    public function brittle(Player $player) {
        $this->brittle[strtolower($player->getName())] = true;
    }

    public function tnttrick(Player $player) {
        $player->getLevel()->setBlock($player->add(1, 1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->subtract(1)->add(0, 1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->add(0, 1, 1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->subtract(0, 0, 1)->add(0, 1), Block::get(Block::TNT));
        $player->getLevel()->addSound(new TNTPrimeSound($player));
    }

    public function tnt(Player $player) {
        $flintnsteel = Item::get(Item::FLINT_STEEL);
        $player->getLevel()->setBlock($player->add(1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->subtract(1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->add(0, 0, 1), Block::get(Block::TNT));
        $player->getLevel()->setBlock($player->subtract(0, 0, 1), Block::get(Block::TNT));
        $player->getLevel()->getBlock($player->add(1))->onActivate($flintnsteel, $player);
        $player->getLevel()->getBlock($player->subtract(1))->onActivate($flintnsteel, $player);
        $player->getLevel()->getBlock($player->add(0, 0, 1))->onActivate($flintnsteel, $player);
        $player->getLevel()->getBlock($player->subtract(0, 0, 1))->onActivate($flintnsteel, $player);
    }

    public function fire(Player $player) {
        $player->teleport($player->floor()->add(0.5, 0, 0.5), $player->yaw, $player->pitch);
        $player->getLevel()->setBlock($player->add(1), Block::get(Block::FIRE));
        $player->getLevel()->setBlock($player->subtract(1), Block::get(Block::FIRE));
        $player->getLevel()->setBlock($player->add(0, 0, 1), Block::get(Block::FIRE));
        $player->getLevel()->setBlock($player->subtract(0, 0, 1), Block::get(Block::FIRE));
    }

    public function squid(Player $player) {
        $chunk = $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4);
        $nbt = new CompoundTag("", ["Pos" => new ListTag("Pos", [new DoubleTag("", floor($player->x)), new DoubleTag("", floor($player->y) + 5), new DoubleTag("", floor($player->z))]), "Motion" => new ListTag("Motion", [new DoubleTag("", 0), new DoubleTag("", 0), new DoubleTag("", 0)]), "Rotation" => new ListTag("Rotation", [new FloatTag("", lcg_value() * 360), new FloatTag("", 0)]), ]);
        $entity = Entity::createEntity("Squid", $chunk, $nbt);
        $entity->spawnToAll();
    }

    public function crash(Player $player) {
        $chunk = $player->getLevel()->getChunk($player->x >> 4, $player->z >> 4);
        $nbt = new CompoundTag("", ["Pos" => new ListTag("Pos", [new DoubleTag("", floor($player->x)), new DoubleTag("", floor($player->y) + 5), new DoubleTag("", floor($player->z))]), "Motion" => new ListTag("Motion", [new DoubleTag("", 0), new DoubleTag("", 0), new DoubleTag("", 0)]), "Rotation" => new ListTag("Rotation", [new FloatTag("", lcg_value() * 360), new FloatTag("", 0)]), ]);
        $entity = Entity::createEntity("Villager", $chunk, $nbt);
        $entity->setDataProperty(16, Entity::DATA_TYPE_BYTE, 5);
        $entity->spawnTo($player);
    }

    public function useless(Player $player) {
        foreach($player->getInventory()->getContents() as $index => $item) {
            $item->setCustomName("Useless");
            $player->getInventory()->setItem($index, $item);
        }
    }

    public function idtheft(Player $player) {
        $this->idtheft[strtolower($player->getName())] = true;
    }

    public function scream(Player $player) {
        $player->getLevel()->addSound(new GhastSound($player));
    }

    public function trip(Player $player) {
        $items = $player->getInventory()->getContents();
        foreach($items as $item) {
            $player->getInventory()->removeItem($item);
            $motion = $player->getDirectionVector()->multiply(0.4);
            $player->getLevel()->dropItem($player->add(0, 1.3, 0), $item, $motion, 40);
        }
    }

    public function potate(Player $player) {
        $this->potate[strtolower($player->getName())] = true;
    }

    public function chat(Player $player, $message) {
        $this->getServer()->getPluginManager()->callEvent($ev = new PlayerChatEvent($player, $message));
        if(!$ev->isCancelled()) {
            $this->getServer()->broadcastMessage($this->getServer()->getLanguage()->translateString($ev->getFormat(), [$ev->getPlayer()->getDisplayName(), $ev->getMessage()]), $ev->getRecipients());
        }
    }

    public function rename(Player $player, $name) {
        $this->rename[strtolower($player->getName())] = true;
        $player->setDisplayName($name);
    }

    public function kick(Player $player, $reason) {
        $player->close("", $reason);
    }

    public function end(Player $player) {
        $player->setHealth(0);
        $this->stop($player);
    }

    public function stop(Player $player) {
        if(isset($this->infall[strtolower($player->getName())])) {
            unset($this->infall[strtolower($player->getName())]);
        }
        if(isset($this->invoid[strtolower($player->getName())])) {
            unset($this->invoid[strtolower($player->getName())]);
        }
        if(isset($this->lavablock[strtolower($player->getName())])) {
            unset($this->lavablock[strtolower($player->getName())]);
        }
        if(isset($this->freeze[strtolower($player->getName())])) {
            unset($this->freeze[strtolower($player->getName())]);
        }
        if(isset($this->babble[strtolower($player->getName())])) {
            unset($this->babble[strtolower($player->getName())]);
        }
        if(isset($this->spin[strtolower($player->getName())])) {
            unset($this->spin[strtolower($player->getName())]);
        }
        if(isset($this->exblock[strtolower($player->getName())])) {
            unset($this->exblock[strtolower($player->getName())]);
        }
        if(isset($this->blind[strtolower($player->getName())])) {
            unset($this->blind[strtolower($player->getName())]);
        }
        if(isset($this->drunk[strtolower($player->getName())])) {
            unset($this->drunk[strtolower($player->getName())]);
        }
        if(isset($this->starve[strtolower($player->getName())])) {
            unset($this->starve[strtolower($player->getName())]);
        }
        if(isset($this->slow[strtolower($player->getName())])) {
            $player->getAttributeMap()->getAttribute(5)->setValue(0.1);
            unset($this->slow[strtolower($player->getName())]);
        }
        if(isset($this->poison[strtolower($player->getName())])) {
            unset($this->poison[strtolower($player->getName())]);
        }
        if(isset($this->unaware[strtolower($player->getName())])) {
            foreach($this->getServer()->getOnlinePlayers() as $p) {
                $player->showPlayer($p);
            }
            unset($this->unaware[strtolower($player->getName())]);
        }
        if(isset($this->rewind[strtolower($player->getName())])) {
            unset($this->rewind[strtolower($player->getName())]);
        }
        if(isset($this->unrewind[strtolower($player->getName())])) {
            unset($this->unrewind[strtolower($player->getName())]);
        }
        if(isset($this->mute[strtolower($player->getName())])) {
            unset($this->mute[strtolower($player->getName())]);
        }
        if(isset($this->spam[strtolower($player->getName())])) {
            unset($this->spam[strtolower($player->getName())]);
        }
        if(isset($this->maim[strtolower($player->getName())])) {
            unset($this->maim[strtolower($player->getName())]);
        }
        if(isset($this->idtheft[strtolower($player->getName())])) {
            $player->setDisplayName($player->getName());
            unset($this->idtheft[strtolower($player->getName())]);
        }
        if(isset($this->potate[strtolower($player->getName())])) {
            unset($this->potate[strtolower($player->getName())]);
        }
        if(isset($this->rename[strtolower($player->getName())])) {
            $player->setDisplayName($player->getName());
            unset($this->rename[strtolower($player->getName())]);
        }
    }

    public function restore(CommandSender $sender) {
        $count = 0;
        foreach($this->explode as $info) {
            $count++;
            $level = $info[0];
            $vector3 = $info[1];
            $block = $info[2];
            $level->setBlock($vector3, $block);
        }
        foreach($this->holes as $info) {
            $count++;
            $level = $info[0];
            $vector3 = $info[1];
            $block = $info[2];
            $level->setBlock($vector3, $block);
        }
        foreach($this->webs as $info) {
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

    public function getStrDirection(Player $player) {
        $rot = ($player->yaw - 90) % 360;
        if($rot < 0.0) {
            $rot += 360.0;
        }
        return $this->getDirection($rot);
    }

    public function getDirection($rot) {
        if(0.0 <= $rot && $rot < 22.5) {
            return "North";
        }
        if(22.5 <= $rot && $rot < 67.5) {
            return "Northeast";
        }
        if(67.5 <= $rot && $rot < 112.5) {
            return "East";
        }
        if(112.5 <= $rot && $rot < 157.5) {
            return "Southeast";
        }
        if(157.5 <= $rot && $rot < 202.5) {
            return "South";
        }
        if(202.5 <= $rot && $rot < 247.5) {
            return "Southwest";
        }
        if(247.5 <= $rot && $rot < 292.5) {
            return "West";
        }
        if(292.5 <= $rot && $rot < 337.5) {
            return "Northwest";
        }
        if(337.5 <= $rot && $rot < 360.0) {
            return "North";
        }
        return null;
    }

}
