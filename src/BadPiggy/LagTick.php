<?php
namespace BadPiggy;

use pocketmine\scheduler\PluginTask;

class LagTick extends PluginTask {
    public function __construct($plugin) {
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }

    public function onRun($currentTick) {
        foreach($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if(isset($this->plugin->lag[strtolower($player->getName())])) {
                if(359 < $player->yaw) {
                    $player->teleport($player, $player->yaw + 1);
                } else {
                    $player->teleport($player, $player->yaw - 358);
                }
            }
        }
    }

}
