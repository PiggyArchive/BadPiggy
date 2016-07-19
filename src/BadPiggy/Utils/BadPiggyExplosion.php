<?php
namespace BadPiggy\Utils;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\item\Item;
use pocketmine\level\Explosion;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Math;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\network\protocol\ExplodePacket;

use pocketmine\utils\Random;

class BadPiggyExplosion extends Explosion {
    private $plugin;

    public function __construct(Position $center, $size, $what = null, $plugin) {
        $this->level = $center->getLevel();
        $this->source = $center;
        $this->size = max($size, 0);
        $this->what = $what;
        $this->plugin = $plugin;
    }

    public function explodeB() {
        $send = [];
        $updateBlocks = [];

        $source = (new Vector3($this->source->x, $this->source->y, $this->source->z))->floor();
        $yield = (1 / $this->size) * 100;

        if($this->what instanceof Entity) {
            $this->level->getServer()->getPluginManager()->callEvent($ev = new EntityExplodeEvent($this->what, $this->source, $this->affectedBlocks, $yield));
            if($ev->isCancelled()) {
                return false;
            } else {
                $yield = $ev->getYield();
                $this->affectedBlocks = $ev->getBlockList();
            }
        }

        $explosionSize = $this->size * 2;
        $minX = Math::floorFloat($this->source->x - $explosionSize - 1);
        $maxX = Math::ceilFloat($this->source->x + $explosionSize + 1);
        $minY = Math::floorFloat($this->source->y - $explosionSize - 1);
        $maxY = Math::ceilFloat($this->source->y + $explosionSize + 1);
        $minZ = Math::floorFloat($this->source->z - $explosionSize - 1);
        $maxZ = Math::ceilFloat($this->source->z + $explosionSize + 1);

        $explosionBB = new AxisAlignedBB($minX, $minY, $minZ, $maxX, $maxY, $maxZ);

        $list = $this->level->getNearbyEntities($explosionBB, $this->what instanceof Entity ? $this->what : null);
        foreach($list as $entity) {
            $distance = $entity->distance($this->source) / $explosionSize;

            if($distance <= 1) {
                $motion = $entity->subtract($this->source)->normalize();

                $impact = (1 - $distance) * ($exposure = 1);

                $damage = (int)((($impact * $impact + $impact) / 2) * 8 * $explosionSize + 1);

                if($this->what instanceof Entity) {
                    $ev = new EntityDamageByEntityEvent($this->what, $entity, EntityDamageEvent::CAUSE_ENTITY_EXPLOSION, $damage);
                } elseif($this->what instanceof Block) {
                    $ev = new EntityDamageByBlockEvent($this->what, $entity, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION, $damage);
                } else {
                    $ev = new EntityDamageEvent($entity, EntityDamageEvent::CAUSE_BLOCK_EXPLOSION, $damage);
                }

                $entity->attack($ev->getFinalDamage(), $ev);
                $entity->setMotion($motion->multiply($impact));
            }
        }

        $air = Item::get(Item::AIR);

        foreach($this->affectedBlocks as $block) {
            $pos = new Vector3($block->x, $block->y, $block->z);
            array_push($this->plugin->explode, array(
                $this->level,
                $pos,
                $block));
            if($block->getId() === Block::TNT) {
                $mot = (new Random())->nextSignedFloat() * M_PI * 2;
                $tnt = Entity::createEntity("PrimedTNT", $this->level->getChunk($block->x >> 4, $block->z >> 4), new CompoundTag("", ["Pos" => new ListTag("Pos", [new DoubleTag("", $block->x + 0.5), new DoubleTag("", $block->y), new DoubleTag("", $block->z + 0.5)]), "Motion" => new ListTag("Motion", [new DoubleTag("", -sin($mot) * 0.02), new DoubleTag("", 0.2), new DoubleTag("", -cos($mot) * 0.02)]), "Rotation" => new ListTag("Rotation", [new FloatTag("", 0), new FloatTag("", 0)]), "Fuse" => new ByteTag("Fuse", mt_rand(10, 30))]));
                $tnt->spawnToAll();
            }

            $this->level->setBlockIdAt($block->x, $block->y, $block->z, 0);

            for($side = 0; $side < 5; $side++) {
                $sideBlock = $pos->getSide($side);
                if(!isset($this->affectedBlocks[$index = Level::blockHash($sideBlock->x, $sideBlock->y, $sideBlock->z)]) and !isset($updateBlocks[$index])) {
                    $this->level->getServer()->getPluginManager()->callEvent($ev = new BlockUpdateEvent($this->level->getBlock($sideBlock)));
                    if(!$ev->isCancelled()) {
                        $ev->getBlock()->onUpdate(Level::BLOCK_UPDATE_NORMAL);
                    }
                    $updateBlocks[$index] = true;
                }
            }
            $send[] = new Vector3($block->x - $source->x, $block->y - $source->y, $block->z - $source->z);
        }

        $pk = new ExplodePacket();
        $pk->x = $this->source->x;
        $pk->y = $this->source->y;
        $pk->z = $this->source->z;
        $pk->radius = $this->size;
        $pk->records = $send;
        $this->level->addChunkPacket($source->x >> 4, $source->z >> 4, $pk);

        return true;
    }
}
