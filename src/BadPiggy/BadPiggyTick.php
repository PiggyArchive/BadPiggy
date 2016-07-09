<?php

namespace BadPiggy;

use pocketmine\scheduler\PluginTask;

class BadPiggyTick extends PluginTask{
	public function __construct($plugin){
		parent::__construct($plugin);
		$this->plugin = $plugin;
	}
	
	public function onRun($currentTick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
			if(isset($this->plugin->spam[strtolower($player->getName())])){
				$messages = array(
					"§aCLICK HERE FOR FREE BACON",
					"§aCHECK OUT MCPEPIG's PLUGINS!",
					"§aTROLOLOLOLLOL",
					"§aSPAM! SPAM! SPAM!",
					"§aCHECK OUT THE PUNISHMENTAL PLUGIN FOR MCPC",
					"§aHI BYE HI BYE HI BYE HI BYE"
				);
				$message = array_rand($messages, 1);
				$message = $messages[$message];
				$player->sendMessage($message);
			}
		}
	}
	
}
