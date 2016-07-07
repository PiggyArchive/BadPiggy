<?php

namespace BadPiggy\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BadPiggyCommand extends VanillaCommand{
	public function __construct($name, $plugin){
		parent::__construct(
			$name, "Troll a player", "/badpiggy <player> <punishment>"
		);
		$this->setPermission("badpiggy.command");
		$this->plugin = $plugin;
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
        if(!$this->testPermission($sender)){
            return true;
        }
        if(count($args) < 3){
            $sender->sendMessage("/badpiggy <player> <punishment>");
            return false;
        }
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if(!$player instanceof Player){
            $sender->sendMessage("§cInvalid player.");
        }
        switch(strtolower($args[1])){
            case "fall":
                if($sender->hasPermission("badpiggy.command.fall")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fall($player);
                $sender->sendMessage("§a" . $player->getName() . " is now falling to their deaths.");
                break;
            case "explode":
                if($sender->hasPermission("badpiggy.command.explode")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->explode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "burn":
                if($sender->hasPermission("badpiggy.command.burn")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])){
                    $sender->sendMessage("/badpiggy burn <seconds>");
                }
                if(!is_numeric($args[2])){
                    $sender->sendMessage("Seconds must be numeric.");
                }
                $this->plugin->burn($player, $arg[2]);
                $sender->sendMessage("§a" . $player->getName() . " is becoming human bacon.");
                break;
            case "void":
                if($sender->hasPermission("badpiggy.command.void")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->void($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space.");
                break;
            case "invoid":
                if($sender->hasPermission("badpiggy.command.invoid")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->invoid($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space forever.");
                break;
            case "lavablock":
                if($sender->hasPermission("badpiggy.command.lavablock")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->lavablock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
                break;               
            case "fexplode":
                if($sender->hasPermission("badpiggy.command.fexplode")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fexplode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "glass":
                if($sender->hasPermission("badpiggy.command.glass")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->glass($player);
                $sender->sendMessage("§a" . $player->getName() . " is uh... stuck.");
                break; 
            case "leveldown":
                if($sender->hasPermission("badpiggy.command.leveldown")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->leveldown($player);
                $sender->sendMessage("§a" . $player->getName() . " has no enchanting rights!");
                break;   
            case "exblock":
                if($sender->hasPermission("badpiggy.command.exblock")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->exblock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
            case "spam":
                if($sender->hasPermission("badpiggy.command.spam")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->spam($player);
                $sender->sendMessage("§a" . $player->getName() . " is too busy reading his emails.");
                break;  
            case "pumpkin":
                if($sender->hasPermission("badpiggy.command.popular")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->popular($player);
                $sender->sendMessage("§a" . $player->getName() . " has lots of fans...");
                break;   
            case "popular":
                if($sender->hasPermission("badpiggy.command.pumpkin")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->pumpkin($player);
                $sender->sendMessage("§a" . $player->getName() . " is a bit creepy...");
                break;  
            case "maim":
                if($sender->hasPermission("badpiggy.command.maim")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->maim($player);
                $sender->sendMessage("§a" . $player->getName() . " won't be able to pick thing ups for a while...");
                break; 
            case "scream":
                if($sender->hasPermission("badpiggy.command.scream")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->scream($player);
                $sender->sendMessage("§a" . $player->getName() . " won't forget this for a while.");
                break; 
            case "end":
                if($sender->hasPermission("badpiggy.command.end")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->end($player);
                $sender->sendMessage("§aAww.. this is the end of the trolling...");
                break;           
            default:
                $sender->sendMessage("§cInvalid Punishments");
                break;
        }
        return true;
	}

}
