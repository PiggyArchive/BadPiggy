<?php
namespace BadPiggy;

use BadPiggy\Utils\BadPiggyExplosion;
use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\MoveEntityPacket;
use pocketmine\network\protocol\MovePlayerPacket;
use pocketmine\Player;

class EventListener implements Listener {
    public function __construct($plugin) {
        $this->plugin = $plugin;
    }

    /**
     * @param BlockBreakEvent $event
     *
     * @priority MONITOR
     * @ignoreCancelled true
     */
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $vector3 = new Vector3($block->x, $block->y, $block->z);
        if(isset($this->plugin->lavablock[strtolower($player->getName())])) {
            unset($this->plugin->lavablock[strtolower($player->getName())]);
            $player->getLevel()->setBlock(new Vector3($block->x, $block->y, $block->z), Block::get(Block::LAVA));
            $event->setCancelled();
        }
        if(isset($this->plugin->exblock[strtolower($player->getName())])) {
            unset($this->plugin->exblock[strtolower($player->getName())]);
            $explosion = new BadPiggyExplosion($player, 4, $player, $this->plugin);
            $explosion->explodeA();
            $explosion->explodeB();
            $event->setCancelled();
        }
        if(isset($this->plugin->potato[strtolower($player->getName())])) {
            $event->setCancelled();
        }
        foreach($this->plugin->display as $info) {
            if($vector3 == $info->floor()) {
                if($player->isOp()) {
                    unset($this->plugin->display[array_search($info, $this->plugin->display)]);
                } else {
                    $event->setCancelled();
                }
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        if(isset($this->plugin->potato[strtolower($player->getName())])) {
            $event->setCancelled();
        }
    }

    public function onDamage(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if($entity instanceof Player) {
            if(isset($this->plugin->invoid[strtolower($entity->getName())])) {
                if($event->getCause() == EntityDamageEvent::CAUSE_VOID) {
                    $event->setCancelled();
                }
            }
        }
        if($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if($damager instanceof Player) {
                if(isset($this->plugin->potato[strtolower($damager->getName())])) {
                    $event->setCancelled();
                }
            }
        }
    }

    public function onPickup(InventoryPickupItemEvent $event) {
        $inventory = $event->getInventory();
        $holder = $inventory->getHolder();
        if($holder instanceof Player) {
            if(isset($this->plugin->maim[strtolower($holder->getName())]) || isset($this->plugin->potato[strtolower($player->getName())])) {
                $event->setCancelled();
            }
        }
    }

    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $message = $event->getmessage();
        if(isset($this->plugin->babble[strtolower($player->getName())])) {
            $event->setMessage(str_shuffle($message));
        }
        if(isset($this->plugin->mute[strtolower($player->getName())])) {
            $event->setCancelled();
        }
    }

    public function onDrop(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        if(isset($this->plugin->potato[strtolower($player->getName())])) {
            $event->setCancelled();
        }
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
            if(isset($this->plugin->unaware[strtolower($p->getName())])) {
                $p->hidePlayer($player);
            }
        }
        if(isset($this->plugin->unaware[strtolower($player->getName())])) {
            foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
                $player->hidePlayer($p);
            }
        }
    }

    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        if(isset($this->plugin->freeze[strtolower($player->getName())])) {
            $event->setCancelled();
        }
        if(isset($this->plugin->infall[strtolower($player->getName())])) {
            if(floor($player->y) < 3 + $player->getLevel()->getSafeSpawn($player)->y) {
                $this->plugin->fall($player);
            }
        }
        if(isset($this->plugin->potato[strtolower($player->getName())])) {
            $event->setCancelled();
        }
        if(isset($this->plugin->brittle[strtolower($player->getName())])) {
            if($event->getTo()->y - $event->getFrom()->y > 0 || $event->getFrom()->y - $event->getTo()->y > 0) {
                echo ($event->getTo()->y - $event->getFrom()->y);
                unset($this->plugin->brittle[strtolower($player->getName())]);
                $player->setHealth(0);
            }
        }
        if(isset($this->plugin->idtheft[strtolower($player->getName())])) {
            $charlist = str_split("abcdefghijklmnopqrstuvwxyz1234567890!\u00a3$%^&*()[];'#,./{}:@~<>?");
            $chars = array_rand($charlist, 8);
            $name = "";
            foreach($chars as $char) {
                $char = $charlist[$char];
                $name = $name . $char;
            }
            $player->setDisplayName($name);
        }
    }

    public function onPacketSend(DataPacketSendEvent $event) {
        if(isset($event->getPacket()->eid)) {
            foreach($this->plugin->getServer()->getOnlinePlayers() as $p) {
                if($p->getId() == $event->getPacket()->eid) {
                    if(isset($this->plugin->potato[strtolower($p->getName())])) {
                        if($event->getPacket() instanceof AddPlayerPacket) {
                            $pk = new AddItemEntityPacket;
                            $pk->eid = $event->getPacket()->eid;
                            $pk->x = $event->getPacket()->x;
                            $pk->y = $event->getPacket()->y;
                            $pk->z = $event->getPacket()->z;
                            $pk->speedX = 0;
                            $pk->speedY = 0;
                            $pk->speedZ = 0;
                            $pk->item = Item::get(Item::POTATO);
                            $pk->metadata = [];
                            $event->getPlayer()->dataPacket($pk);
                            $event->setCancelled();
                        }
                        if($event->getPacket() instanceof MovePlayerPacket) {
                            $pk = new MoveEntityPacket;
                            $pk->entities = [[$event->getPacket()->eid, $event->getPacket()->x, $event->getPacket()->y + $p->getEyeHeight(), $event->getPacket()->z, null, null, null]];
                            $event->getPlayer()->dataPacket($pk);
                            $event->setCancelled();
                        }
                    }
                }
            }
        }
    }

}
