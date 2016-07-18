<?php
namespace BadPiggy\Commands;

use pocketmine\command\defaults\VanillaCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class BadPiggyCommand extends VanillaCommand {
    public function __construct($name, $plugin) {
        parent::__construct($name, "Troll a player", "/badpiggy <player> <punishment>");
        $this->setPermission("badpiggy.command");
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, $currentAlias, array $args) {
        if(!$this->testPermission($sender)) {
            return true;
        }
        if(!isset($args[0])) {
            $sender->sendMessage("/badpiggy <punishment|restore|list> <player>");
            return false;
        }
        $player = null;
        $punishment = null;
        if($args[0] !== "list" && $args[0] !== "restore") {
            if(isset($args[1])) {
                $player = $this->plugin->getServer()->getPlayer($args[0]);
                if(!$player instanceof Player) {
                    $player = $this->plugin->getServer()->getPlayer($args[1]);
                    if(!$player instanceof Player) {
                        $sender->sendMessage("§cInvalid player.");
                        return false;
                    }
                    $punishment = $args[0];
                } else {
                    $punishment = $args[1];
                }
            } else {
                $sender->sendMessage("/badpiggy <punishment> <player>");
                return false;
            }
        }else{
            $punishment = $args[0];
        }
        switch(strtolower($punishment)) {
            case "fall":
                if(!$sender->hasPermission("badpiggy.command.fall")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fall($player);
                $sender->sendMessage("§a" . $player->getName() . " is now falling to their deaths.");
                break;
            case "explode":
                if(!$sender->hasPermission("badpiggy.command.explode")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->explode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "strike":
                if(!$sender->hasPermission("badpiggy.command.strike")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->strike($player);
                $sender->sendMessage("§a" . $player->getName() . " became the flash.");
                break;
            case "burn":
                if(!$sender->hasPermission("badpiggy.command.burn")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])) {
                    $sender->sendMessage("/badpiggy burn <seconds>");
                    return false;
                }
                if(!is_numeric($args[2])) {
                    $sender->sendMessage("Seconds must be numeric.");
                    return false;
                }
                $this->plugin->burn($player, $args[2]);
                $sender->sendMessage("§a" . $player->getName() . " is becoming human bacon.");
                break;
            case "infall":
                if(!$sender->hasPermission("badpiggy.command.infall")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->infall($player);
                $sender->sendMessage("§a" . $player->getName() . " is clearly on a trampoline.");
                break;
            case "web":
                if(!$sender->hasPermission("badpiggy.command.web")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->web($player);
                $sender->sendMessage("§a" . $player->getName() . " is stuck.");
                break;
            case "void":
                if(!$sender->hasPermission("badpiggy.command.void")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->void($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space.");
                break;
            case "invoid":
                if(!$sender->hasPermission("badpiggy.command.invoid")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->invoid($player);
                $sender->sendMessage("§a" . $player->getName() . " is now in space forever.");
                break;
            case "lavablock":
                if(!$sender->hasPermission("badpiggy.command.lavablock")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->lavablock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
                break;
            case "hole":
                if(!$sender->hasPermission("badpiggy.command.hole")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->hole($player);
                $sender->sendMessage("§a" . $player->getName() . " fell into a hole & went splat.");
                break;
            case "teleport":
                if(!$sender->hasPermission("badpiggy.command.teleport")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->teleport($player);
                $sender->sendMessage("§a" . $player->getName() . " is lost.");
                break;
            case "freeze":
                if(!$sender->hasPermission("badpiggy.command.freeze")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->freeze($player);
                $sender->sendMessage("§a" . $player->getName() . " is now a statue.");
                break;
            case "fexplode":
                if(!$sender->hasPermission("badpiggy.command.fexplode")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fexplode($player);
                $sender->sendMessage("§a" . $player->getName() . " went boom.");
                break;
            case "blind":
                if(!$sender->hasPermission("badpiggy.command.blind")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->blind($player);
                $sender->sendMessage("§a" . $player->getName() . " needs glasses...");
                break;
            case "drunk":
                if(!$sender->hasPermission("badpiggy.command.drunk")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->drunk($player);
                $sender->sendMessage("§a" . $player->getName() . " drank too much beer...");
                break;
            case "starve":
                if(!$sender->hasPermission("badpiggy.command.starve")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->starve($player);
                $sender->sendMessage("§a" . $player->getName() . " has been stranded on an island with no food...");
                break;
            case "slow":
                if(!$sender->hasPermission("badpiggy.command.slow")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->slow($player);
                $sender->sendMessage("§a" . $player->getName() . " is as slow as a sloth!");
                break;
            case "poison":
                if(!$sender->hasPermission("badpiggy.command.poison")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->poison($player);
                $sender->sendMessage("§a" . $player->getName() . " got hungry and ate too much raw chicken.");
                break;
            case "strip":
                if(!$sender->hasPermission("badpiggy.command.strip")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->strip($player);
                $sender->sendMessage("§a" . $player->getName() . " lost his items to the police.");
                break;
            case "glass":
                if(!$sender->hasPermission("badpiggy.command.glass")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->glass($player);
                $sender->sendMessage("§a" . $player->getName() . " is uh stuck... in the air.");
                break;
            case "shoot":
                if(!$sender->hasPermission("badpiggy.command.shoot")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->shoot($player);
                $sender->sendMessage("§a" . $player->getName() . " is a human cannonball.");
                break;
            case "anvil":
                if(!$sender->hasPermission("badpiggy.command.anvil")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->anvil($player);
                $sender->sendMessage("§a" . $player->getName() . " got squashed.");
                break;
            case "babble":
                if(!$sender->hasPermission("badpiggy.command.babble")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->babble($player);
                $sender->sendMessage("§a" . $player->getName() . " babbles too much...");
                break;
            case "spin":
                if(!$sender->hasPermission("badpiggy.command.spin")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->spin($player);
                $sender->sendMessage("§a" . $player->getName() . " must be dizzy...");
                break;
            case "unaware":
                if(!$sender->hasPermission("badpiggy.command.unaware")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->unaware($player);
                $sender->sendMessage("§a" . $player->getName() . " is human-blind.");
                break;
            case "leveldown":
                if(!$sender->hasPermission("badpiggy.command.leveldown")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $result = $this->plugin->leveldown($player);
                if(!$result) {
                    $sender->sendMessage("§cYour server might not support xp. If you think this is a mistake, please contact MCPEPIG.");
                    return false;
                }
                $sender->sendMessage("§a" . $player->getName() . " has no enchanting rights!");
                break;
            case "surround":
                if(!$sender->hasPermission("badpiggy.command.surround")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->surround($player);
                $sender->sendMessage("§a" . $player->getName() . " is surrounded by cops!");
                break;
            case "flamingarrow":
                if(!$sender->hasPermission("badpiggy.command.flamingarrow")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->flamingarrow($player);
                $sender->sendMessage("§a" . $player->getName() . " got shot!");
                break;
            case "night":
                if(!$sender->hasPermission("badpiggy.command.night")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->night($player);
                $sender->sendMessage("§a" . $player->getName() . " is hallucinating.");
                break;
            case "rewind":
                if(!$sender->hasPermission("badpiggy.command.rewind")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->rewind($player);
                $sender->sendMessage("§a" . $player->getName() . " is dizzy.");
                break;
            case "slap":
                if(!$sender->hasPermission("badpiggy.command.slap")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->slap($player);
                $sender->sendMessage("§a" . $player->getName() . " got slapped.");
                break;
            case "lag":
                if(!$sender->hasPermission("badpiggy.command.lag")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->lag($player);
                $sender->sendMessage("§a" . $player->getName() . " is lagging.");
                break;
            case "exblock":
                if(!$sender->hasPermission("badpiggy.command.exblock")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->exblock($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna have a nice suprise.");
                break;
            case "mute":
                if(!$sender->hasPermission("badpiggy.command.mute")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->mute($player);
                $sender->sendMessage("§a" . $player->getName() . " got duck tape on his mouth.");
                break;
            case "spam":
                if(!$sender->hasPermission("badpiggy.command.spam")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->spam($player);
                $sender->sendMessage("§a" . $player->getName() . " is too busy reading his emails.");
                break;
            case "cage":
                if(!$sender->hasPermission("badpiggy.command.cage")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->cage($player);
                $sender->sendMessage("§a" . $player->getName() . " is my your pet.");
                break;
            case "fakeop":
                if(!$sender->hasPermission("badpiggy.command.fakeop")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fakeop($player);
                $sender->sendMessage("§a" . $player->getName() . " thinks he's op.");
                break;
            case "popup":
                if(!$sender->hasPermission("badpiggy.command.popup")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->popup($player);
                $sender->sendMessage("§a" . $player->getName() . " has problems carrying all that trash.");
                break;
            case "popular":
                if(!$sender->hasPermission("badpiggy.command.popular")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->popular($player);
                $sender->sendMessage("§a" . $player->getName() . " has lots of fans...");
                break;
            case "display":
                if(!$sender->hasPermission("badpiggy.command.display")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->display($player);
                $sender->sendMessage("§a" . $player->getName() . " is on display at the Museum of Pigs.");
                break;
            case "pumpkin":
                if(!$sender->hasPermission("badpiggy.command.pumpkin")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->pumpkin($player);
                $sender->sendMessage("§a" . $player->getName() . " is a bit creepy...");
                break;
            case "armour":
                if(!$sender->hasPermission("badpiggy.command.armour")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->armour($player);
                $sender->sendMessage("§a" . $player->getName() . " has very good armor...");
                break;
            case "tree":
                if(!$sender->hasPermission("badpiggy.command.tree")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->tree($player);
                $sender->sendMessage("§a" . $player->getName() . " is apart of a tree...");
                break;
            case "maim":
                if(!$sender->hasPermission("badpiggy.command.maim")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->maim($player);
                $sender->sendMessage("§a" . $player->getName() . "'s arms were amputated.");
                break;
            case "brittle":
                if(!$sender->hasPermission("badpiggy.command.brittle")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->brittle($player);
                $sender->sendMessage("§a" . $player->getName() . " has a condition recently discovered called no-jump-or-die.");
                break;
            case "tnttrick":
                if(!$sender->hasPermission("badpiggy.command.tnttrick")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->tnttrick($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna be freaked out from these fake tnt blocks.");
                break;
            case "tnt":
                if(!$sender->hasPermission("badpiggy.command.tnt")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->tnt($player);
                $sender->sendMessage("§a" . $player->getName() . " is gonna go boom.");
                break;
            case "fire":
                if(!$sender->hasPermission("badpiggy.command.firre")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->fire($player);
                $sender->sendMessage("§a" . $player->getName() . " is being barbecued.");
                break;
            case "squid":
                if(!$sender->hasPermission("badpiggy.command.squid")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->squid($player);
                $sender->sendMessage("§a" . $player->getName() . " must save the squid!");
                break;
            case "crash":
                if(!$sender->hasPermission("badpiggy.command.crash")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->crash($player);
                $sender->sendMessage("§a" . $player->getName() . " was involved in a car crash.");
                break;
            case "useless":
                if(!$sender->hasPermission("badpiggy.command.useless")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->useless($player);
                $sender->sendMessage("§a" . $player->getName() . " has so much trash....");
                break;
            case "idtheft":
                if(!$sender->hasPermission("badpiggy.command.idtheft")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->idtheft($player);
                $sender->sendMessage("§a" . $player->getName() . " is an organized criminal.");
                break;
            case "scream":
                if(!$sender->hasPermission("badpiggy.command.scream")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->scream($player);
                $sender->sendMessage("§a" . $player->getName() . " won't forget this for a while.");
                break;
            case "trip":
                if(!$sender->hasPermission("badpiggy.command.trip")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->trip($player);
                $sender->sendMessage("§a" . $player->getName() . " tripped and dropped his stuff.");
                break;
            case "potate":
                if(!$sender->hasPermission("badpiggy.command.potate")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->potate($player);
                $sender->sendMessage("§a" . $player->getName() . " is vomitting potatoes.");
                break;
            case "chat":
                if(!$sender->hasPermission("badpiggy.command.chat")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])) {
                    $sender->sendMessage("/badpiggy chat " . $player->getName() . " <message>");
                    return false;
                }
                $pee = array_shift($args);
                $poop = array_shift($args);
                $message = implode(" ", $args);
                $this->plugin->chat($player, $message);
                $sender->sendMessage("§a" . $player->getName() . " is being mine controlled 0.o");
                break;
            case "kick":
                if(!$sender->hasPermission("badpiggy.command.kick")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])) {
                    $sender->sendMessage("/badpiggy kick " . $player->getName() . " <reason>");
                    return false;
                }
                $pee = array_shift($args);
                $poop = array_shift($args);
                $reason = implode(" ", $args);
                $this->plugin->kick($player, $reason);
                $sender->sendMessage("§a" . $player->getName() . " got kicked in the leg.");
                break;
            case "rename":
                if(!$sender->hasPermission("badpiggy.command.rename")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])) {
                    $sender->sendMessage("/badpiggy rename " . $player->getName() . " <name>");
                    return false;
                }
                $pee = array_shift($args);
                $poop = array_shift($args);
                $name = implode(" ", $args);
                $this->plugin->rename($player, $name);
                $sender->sendMessage("§a" . $player->getName() . " changed his name.");
                break;
            case "sudo":
                if(!$sender->hasPermission("badpiggy.command.sudo")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                if(!isset($args[2])) {
                    $sender->sendMessage("/badpiggy sudo " . $player->getName() . " <command>");
                    return false;
                }
                $pee = array_shift($args);
                $poop = array_shift($args);
                $command = implode(" ", $args);
                $this->plugin->sudo($player, $command);
                $sender->sendMessage("§a" . $player->getName() . " is being mine controlled 0.o");
                break;
            case "end":
                if(!$sender->hasPermission("badpiggy.command.end")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->end($player);
                $sender->sendMessage("§aAww.. this is the end of the trolling...");
                break;
            case "stop":
                if(!$sender->hasPermission("badpiggy.command.stop")) {
                    $sender->sendMessage("§cYou do not have permission to use this subcommand.");
                    return false;
                }
                $this->plugin->stop($player);
                $sender->sendMessage("§aAww.. this is the end of the trolling...");
                break;
            case "list":
                if(!isset($args[1])) {
                    $page = 1;
                } else {
                    $page = $args[1];
                }
                if(!is_numeric($page)) {
                    $page = 1;
                }
                $maxpage = 13;
                if($page > $maxpage) {
                    $page = $maxpage;
                }
                switch($page) {
                    case 0:
                    case 1:
                        $sender->sendMessage("--- Punishments Page 1 of " . $maxpage . " ---\n§2fall\n§2explode\n§2burn\n§2end");
                        break;
                    case 2:
                        $sender->sendMessage("--- Punishments Page 2 of " . $maxpage . " ---\n§2void\n§2invoid\n§2lavablock\n§2fexplode");
                        break;
                    case 3:
                        $sender->sendMessage("--- Punishments Page 3 of " . $maxpage . " ---\n§2glass\n§2babble\n§2leveldown\n§2exblock");
                        break;
                    case 4:
                        $sender->sendMessage("--- Punishments Page 4 of " . $maxpage . " ---\n§2popular\n§2pumpkin\n§2armour\n§2maim");
                        break;
                    case 5:
                        $sender->sendMessage("--- Punishments Page 5 of " . $maxpage . " ---\n§2scream\n§2strip\n§afreeze\n§amute");
                        break;
                    case 6:
                        $sender->sendMessage("--- Punishments Page 6 of " . $maxpage . " ---\n§2unaware\n§aweb\n§auseless\nhole");
                        break;
                    case 7:
                        $sender->sendMessage("--- Punishments Page 7 of " . $maxpage . " ---\n§2blind\n§adrunk\n§starve\nslow");
                        break;
                    case 8:
                        $sender->sendMessage("--- Punishments Page 8 of " . $maxpage . " ---\n§2poison\n§afakeop\nidtheft\nnight");
                        break;
                    case 9:
                        $sender->sendMessage("--- Punishments Page 9 of " . $maxpage . " ---\n§2rewind\n§asquid\nstop\nkick");
                        break;
                    case 10:
                        $sender->sendMessage("--- Punishments Page 10 of " . $maxpage . " ---\n§2crash\n§2popup\ntrip\n§2anvil");
                        break;
                    case 11:
                        $sender->sendMessage("--- Punishments Page 10 of " . $maxpage . " ---\n§2fire\n§2teleport\n§2display\n§2cage");
                        break;
                    case 12:
                        $sender->sendMessage("--- Punishments Page 10 of " . $maxpage . " ---\n§2potate\n§2tree\n§2flamingarrow\n§2spin");
                        break;
                    case 13:
                        $sender->sendMessage("--- Punishments Page 10 of " . $maxpage . " ---\n§2shoot\n§2slap\n§2sudo\n§2surround");
                        break;
                    case 14:
                        $sender->sendMessage("--- Punishments Page 10 of " . $maxpage . " ---\n");
                        break;
                }
                break;
            case "restore":
                $sender->sendMessage("§aRestoring damage...");
                $this->plugin->restore($sender);
                break;
            default:
                $sender->sendMessage("§cUnknown punishment. Try /badpiggy list for a list of punishments");
                break;
        }
        return true;
    }

}
