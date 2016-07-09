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
        if(isset($args[0])){
            if($args[0] == "list"){
                if(!isset($args[1])){
                    $page = 1;
                }else{       
                    $page = $args[1];
                }
                if(!is_numeric($page)){
                    $page = 1;
                }
                if($page > 6){
                    $page = 6;
                }
                switch($page){
                    case 0:
                    case 1:
                        $sender->sendMessage("--- Punishments Page 1 of 6---\n§2fall\n§2explode\n§2burn\n§2end");
                        break;
                    case 2:
                        $sender->sendMessage("--- Punishments Page 2 of 6 ---\n§2void\n§2invoid\n§2lavablock\n§2fexplode");
                        break;
                    case 3:
                        $sender->sendMessage("--- Punishments Page 3 of 6 ---\n§2glass\n§2babble\n§2leveldown\n§2exblock");
                        break;
                    case 4:
                        $sender->sendMessage("--- Punishments Page 4 of 6 ---\n§2popular\n§2pumpkin\n§2armour\n§2maim");
                        break;
                    case 5:
                        $sender->sendMessage("--- Punishments Page 5 of 6 ---\n§2scream\n§2strip\n§afreeze\n§amute");
                        break;
                    case 6:
                        $sender->sendMessage("--- Punishments Page 6 of 6 ---\n§2unaware\n§aweb\n§auseless");
                        break;
                }
                return true;
            }
            if($args[0] == "restore"){
                $this->plugin->restore();
                $sender->sendMessage("§aRestoring damage...");
                return true;
            }
        }
        if(count($args) < 2){
            $sender->sendMessage("/badpiggy <player> <punishment>");
            return false;
        }
        if($args )
        $player = $this->plugin->getServer()->getPlayer($args[0]);
        if(!$player instanceof Player){
            $sender->sendMessage("§cInvalid player.");
            return false;
        }
        switch(strtolower($args[1])){
            case "fall":
                if(!$sender->hasPermission("badpiggy.command.fall")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fall($player);
                $sender->sendMessage("§a" . $player->getName() . " is now falling to their deaths.");
                break;
            case "explode":
                if(!$sender->hasPermission("badpiggy.command.explode")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->explode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "strike":
                if(!$sender->hasPermission("badpiggy.command.strike")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->strike($player);
                $sender->sendMessage("§a" . $player->getName() . " became the flash.");
                break;
            case "burn":
                if(!$sender->hasPermission("badpiggy.command.burn")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])){
                    $sender->sendMessage("/badpiggy burn <seconds>");
                }
                if(!is_numeric($args[2])){
                    $sender->sendMessage("Seconds must be numeric.");
                }
                $this->plugin->burn($player, $args[2]);
                $sender->sendMessage("§a" . $player->getName() . " is becoming human bacon.");
                break;
            case "web":
                if(!$sender->hasPermission("badpiggy.command.web")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->web($player);
                $sender->sendMessage("§a" . $player->getName() . " is stuck.");
                break;
            case "void":
                if(!$sender->hasPermission("badpiggy.command.void")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->void($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space.");
                break;
            case "invoid":
                if(!$sender->hasPermission("badpiggy.command.invoid")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->invoid($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space forever.");
                break;
            case "lavablock":
                if(!$sender->hasPermission("badpiggy.command.lavablock")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->lavablock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
                break; 
            case "freeze":
                if(!$sender->hasPermission("badpiggy.command.freeze")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->freeze($player);
                $sender->sendMessage("§a" . $player->getName() . " is now a statue.");
                break;              
            case "fexplode":
                if(!$sender->hasPermission("badpiggy.command.fexplode")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fexplode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "strip":
                if(!$sender->hasPermission("badpiggy.command.strip")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->strip($player);
                $sender->sendMessage("§a" . $player->getName() . " lost his items to the police.");
                break;
            case "glass":
                if(!$sender->hasPermission("badpiggy.command.glass")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->glass($player);
                $sender->sendMessage("§a" . $player->getName() . " is uh... stuck.");
                break; 
            case "babble":
                if(!$sender->hasPermission("badpiggy.command.babble")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->babble($player);
                $sender->sendMessage("§a" . $player->getName() . " babbles too much...");
                break; 
            case "unaware":
                if(!$sender->hasPermission("badpiggy.command.unaware")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->unaware($player);
                $sender->sendMessage("§a" . $player->getName() . " is human-blind.");
                break; 
            case "leveldown":
                if(!$sender->hasPermission("badpiggy.command.leveldown")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->leveldown($player);
                $sender->sendMessage("§a" . $player->getName() . " has no enchanting rights!");
                break;   
            case "exblock":
                if(!$sender->hasPermission("badpiggy.command.exblock")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->exblock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
                break;
            case "mute":
                if(!$sender->hasPermission("badpiggy.command.mute")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->mute($player);
                $sender->sendMessage("§a" . $player->getName() . " got duck tape on his mouth.");
                break;
            case "spam":
                if(!$sender->hasPermission("badpiggy.command.spam")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->spam($player);
                $sender->sendMessage("§a" . $player->getName() . " is too busy reading his emails.");
                break;  
            case "popular":
                if(!$sender->hasPermission("badpiggy.command.popular")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->popular($player);
                $sender->sendMessage("§a" . $player->getName() . " has lots of fans...");
                break;     
            case "pumpkin":
                if(!$sender->hasPermission("badpiggy.command.pumpkin")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->pumpkin($player);
                $sender->sendMessage("§a" . $player->getName() . " is a bit creepy...");
                break;  
            case "armour":
                if(!$sender->hasPermission("badpiggy.command.armour")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->armour($player);
                $sender->sendMessage("§a" . $player->getName() . " has very good armor...");
                break; 
            case "maim":
                if(!$sender->hasPermission("badpiggy.command.maim")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->maim($player);
                $sender->sendMessage("§a" . $player->getName() . " won't be able to pick thing ups for a while...");
                break; 
            case "useless":
                if(!$sender->hasPermission("badpiggy.command.useless")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->useless($player);
                $sender->sendMessage("§a" . $player->getName() . " has so much trash....");
                break; 
            case "scream":
                if(!$sender->hasPermission("badpiggy.command.scream")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->scream($player);
                $sender->sendMessage("§a" . $player->getName() . " won't forget this for a while.");
                break; 
            case "end":
                if(!$sender->hasPermission("badpiggy.command.end")){
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->end($player);
                $sender->sendMessage("§aAww.. this is the end of the trolling...");
                break;   
            default:
                $sender->sendMessage("§cUnknown punishment. Try /badpiggy list for a list of punishments");
                break;
        }
        return true;
	}

}
