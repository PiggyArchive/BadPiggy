<?php
namespace BadPiggy;

use pocketmine\item\Item;
use pocketmine\network\protocol\SetTimePacket;
use pocketmine\scheduler\PluginTask;

class BadPiggyTick extends PluginTask {
    public function __construct($plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($currentTick) {
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if(isset($this->plugin->spam[strtolower($player->getName())])) {
                $messages = array(
                    "§aCLICK HERE FOR FREE BACON",
                    "§aCHECK OUT MCPEPIG's PLUGINS!",
                    "§aTROLOLOLOLLOL",
                    "§aSPAM! SPAM! SPAM!",
                    "§aCHECK OUT THE PUNISHMENTAL PLUGIN FOR MCPC",
                    "§aHI BYE HI BYE HI BYE HI BYE");
                $message = array_rand($messages, 1);
                $message = $messages[$message];
                $player->sendMessage($message);
            }
            if(isset($this->plugin->spin[strtolower($player->getName())])) {
                $player->teleport($player, $player->yaw + 1, $player->pitch / 180 * M_PI);
            }
            if(isset($this->plugin->rewind[strtolower($player->getName())])) {
                $pk = new SetTimePacket();
                $pk->time = 0;
                if(isset($this->plugin->unrewind[strtolower($player->getName())])) {
                    if($this->plugin->unrewind[strtolower($player->getName())] == 23549) {
                        $this->plugin->rewind[strtolower($player->getName())] = $this->plugin->rewind[strtolower($player->getName())] - 50;
                        $pk->time = $this->plugin->rewind[strtolower($player->getName())];
                        if($this->plugin->rewind[strtolower($player->getName())] < 0) {
                            $pk->time = 0;
                            $this->plugin->unrewind[strtolower($player->getName())] = 0;
                            $this->plugin->rewind[strtolower($player->getName())] = 23549;
                        }
                    } else {
                        $this->plugin->unrewind[strtolower($player->getName())] = $this->plugin->unrewind[strtolower($player->getName())] + 50;
                        $pk->time = $this->plugin->unrewind[strtolower($player->getName())];
                        if($this->plugin->unrewind[strtolower($player->getName())] < 0) {
                            $pk->time = 23549;
                            $this->plugin->unrewind[strtolower($player->getName())] = 23549;
                        }
                    }
                }
                $pk->started = $player->getLevel()->stopTime == false;
                $player->dataPacket($pk);
            }
            if(isset($this->plugin->potate[strtolower($player->getName())])) {
                $motion = $player->getDirectionVector()->multiply(0.4);
                $player->getLevel()->dropItem($player->add(0, 1.3, 0), Item::get(Item::POTATO, 0, 64), $motion, 40);
            }
        }
    }

}
