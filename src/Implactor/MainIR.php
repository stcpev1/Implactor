<?php
/*
*
*  _____                 _            _             
* |_   _|               | |          | |            
*   | |  _ __ ___  _ __ | | __ _  ___| |_ ___  _ __ 
*   | | | '_ ` _ \| '_ \| |/ _` |/ __| __/ _ \| '__|
*  _| |_| | | | | | |_) | | (_| | (__| || (_) | |   
* |_____|_| |_| |_| .__/|_|\__,_|\___|\__\___/|_|   
*                 | |                               
*                 |_|                               
*
* Implactor (1.4.x | 1.5.x)
* A plugin with some features for Minecraft: Bedrock!
* --- = ---
*
* Team: ImpladeDeveloped
* 2018 (c) Zadezter
*
*/

declare(strict_types=1);

namespace Implactor;

use pocketmine\Player;
use pocketmine\level\Level;
use pocketmine\Server;

use pocketmine\plugin\PluginBase;
use pocketmine\plugin\Plugin;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat as IR;
use pocketmine\scheduler\PluginTask;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Location;
use pocketmine\level\Position;
use pocketmine\level\particle\DestroyBlockParticle as FrostBloodParticle;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;

use Implactor\particles\HubParticle;
use Implactor\particles\DeathParticle;

class MainIR extends PluginBase implements Listener {
	
  public function onEnable(): void{
  	$this->getLogger()->info(IR::GREEN . "Implactor plugin is now online!");
         $this->getServer()->getScheduler()->scheduleRepeatingTask(new HubParticle($this, $this), 20);
         $this->getServer()->getPluginManager()->registerEvents($this, $this);
       }
  
         public function onDisable(): void{
          $this->getLogger()->info(IR::RED . "Implactor plugin is now offline!");
          $this->getServer()->shutdown();
        }
  
         public function onPlayerLogin(PlayerLoginEvent $ev): void{
          $ev->getPlayer()->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
	}
	
	     public function onPlayerJoin(PlayerJoinEvent $ev): void{
             $player = $ev->getPlayer();
             $ev->setJoinMessage("§8[§a+§8] §a{$player->getName()}");
       }
         
          public function onHit(EntityDamageEvent $ev): void{
           if ($ev->getEntity() instanceof Player) {
            if ($ev instanceof EntityDamageByEntityEvent) {
                $ev->getEntity()->getLevel()->addParticle(new FrostBloodParticle($ev->getEntity(), Block::get(57)));
                  }
                }
              }
     
         public function onPlayerQuit(PlayerQuitEvent $ev): void{
         $player = $ev->getPlayer();
         $ev->setQuitMessage("§8[§c-§8] §c{$player->getName()}");   
      }
  
  
          public function onPlayerDeath(PlayerDeathEvent $ev): void{
          $player = $ev->getPlayer();
          $this->getServer()->getScheduler()->scheduleDelayedTask(new DeathParticle($this, $player), 20);
         }
         
          public function onRespawn(PlayerRespawnEvent $ev) : void{
          $player = $ev->getPlayer();
            $title = "§l§cYOU DIED!";
             $subtitle = "§eThat's hurt, ouch!";
              $player->addTitle($title, $subtitle);
         }
  
                      public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
                      if(strtolower($command->getName()) == "hub") {
                       if($sender->hasPermission("implactor.hub")) {
                          $pos = $sender->getLevel()->getSpawnLocation();
                          $sender->teleport($pos);
                          $sender->addTitle("§7§l[§eHUB§7]§r", "§aReturning§f...");
                          $sender->sendMessage(IR::GRAY. "-------" .IR::WHITE. "\n Returning to hub..." .IR::GRAY. "\n-------");
                          return true;
                     }
                 }
                 
                       if(strtolower($command->getName()) == "sethub") {
                       	if($sender->hasPermission("implactor.sethub")) {
                       	   if($sender->isOp()){
                       	  $sender->getLevel()->setSpawnLocation($sender);
                             $sender->getServer()->setDefaultLevel($sender->getLevel());
                             $sender->sendMessage(IR::YELLOW . "You have successfully set a main hub!");
                             return true;
                         }
                      }
                   }
                        
                        if(strtolower($command->getName()) == "fly") {
                       	if($sender->hasPermission("implactor.fly")) {
                       	   if($sender->isOp()){
                                if(!$sender->getAllowFlight()){
                                 $sender->setAllowFlight(true);
                                 $sender->sendMessage("§8§l(§a!§8)§r §7You have §aenabled §7your fly ability!");
                              }else{
                                 $sender->setAllowFlight(false);
                                 $sender->setFlying(false);
                                 $sender->sendMessage("§8§l(§c!§8)§r §7You have §cdisabled §7your fly ability!");
                                 }
                               }else{
                                 $sender->sendMessage("§cYou have no permission allowed to use §fFly §ccommand!");
                                 return false;
                                }
                                return true;
                              }
                            }
			      
                           if(strtolower($command->getName()) == "gmc") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                       	   $sender->setGamemode(Player::CREATIVE);
                           $sender->sendMessage("§eChanged your gamemode to §aCreative §emode!");
                           return true;
                       }
                     }
                   }
                     
                           if(strtolower($command->getName()) == "gms") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                              $sender->setGamemode(Player::SURVIVAL); 
                              $sender->sendMessage("§eChanged your gamemode to §cSurvival §emode!");
                              return true;
                       }
                     }
                   }
                           if(strtolower($command->getName()) == "gma") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                       	   $sender->setGamemode(Player::ADVENTURE);
                           $sender->sendMessage("§eChanged your gamemode to §cAdventure §emode!");
                           return true;
                        }
                      }
                    }
                       
                           if(strtolower($command->getName()) == "gmspc") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                              $sender->setGamemode(Player::SPECTATOR);
                              $sender->sendMessage("§eChanged your gamemode to §bSpectator §emode!");
                              return true;
                              }
                            }
                          }
                          
                           if(strtolower($command->getName()) == "nick") {
                            if($sender->hasPermission("implactor.nick")){
                            if(count($args) > 0){
                            if($args[0] == "off"){
                            $sender->setDisplayName($sender->getName());
                             $sender->sendMessage("§l§8(§c!§8)§r §7You have set your nickname as §l§cdefault§r§7!");
                          }else{
                              $sender->setDisplayName($args[0]);
                            $sender->sendMessage("§l§8(§a!§8)§r §7You have set your nickname as §l§a" . $args[0] . "§7!");
                             }
                         }else{
                            $sender->sendMessage("§l§8(§6!§8)§r §l§cCommand usage§8:§r§7 /nick <name|off>");
                            return false;
                             }
                          }else{
                             $sender->sendMessage("§cYou have no permission allowed to use §bNick §ccommand!");
                              return false;
                              }
                              return true;
                           }
           
                                           if(strtolower($command->getName()) == "wild") {
                                             if($sender->hasPermission("implactor.wild")){
                                             $x = mt_rand(1, 999);
                                             $z = mt_rand(1, 999);
                                             $y = $sender->getLevel()->getHighestBlockAt($x, $z) + 1;
                                             $sender->teleport(new Position($x, $y, $z, $sender->getLevel()));
                                             $sender->addTitle("§7§l[§dWILD§7]§r", "§fRandom Teleporting...");
                                             $sender->sendMessage("§7-------\n §cTeleporting to wild... §7\n-------");
                                             return true;
                                           }
                                        }
                                        
                                            if(strtolower($command->getName()) == "kill") {
                                             if($sender->hasPermission("implactor.kill")){
                                            if($sender->isOp()){
                                            $sender->setHealth(0);
                                            $sender->sendMessage("§cMove like pain, be steady like a death!");
                                            return true;
                                           }
                                         }
                                      }
                                      
                                    if(strtolower($command->getName()) == "ping") {
                                     if($sender->hasPermission("implactor.ping")){
                                     $sender->sendMessage("§aPing Status§c:");
                                     $sender->sendMessage("§b" . $sender->getPing() . "§f ms §6on connection!");
                                     return true;
                                  }
                              }
                              
                              if(strtolower($command->getName()) == "clearitem") {
                                     if($sender->hasPermission("implactor.clearinventory")){
                                     if($sender->isOp()){
                                    $sender->getInventory()->clearAll();
                                    $sender->sendMessage("§aAll §eitems §awas cleared successfully from your inventory!");
                                    return true;
                                    }
                                 }
                               }
                                 
                                 if(strtolower($command->getName()) == "cleararmor") {
                                     if($sender->hasPermission("implactor.cleararmor")){
                                     if($sender->isOp()){
                                    $sender->getArmorInventory()->clearAll();
                                    $sender->sendMessage("§eYour armors §awas cleared successfully from your body!");
                                    return true;
                                    }
                                  }
                                }
                                    
                                    if(strtolower($command->getName()) == "clearall") {
                                     if($sender->hasPermission("implactor.clearall")){
                                     if($sender->isOp()){	                                              
                                    $sender->getInventory()->clearAll();
                                    $sender->getArmorInventory()->clearAll();
                                    $sender->sendMessage("§aAll §eitems §aand §earmors §awas cleared successfully from your inventory and body!");
                                    return true;
                                    }
                                 }
                               }
                                     
                                     if(strtolower($command->getName()) == "heal") {
                                     if($sender->hasPermission("implactor.heal")){
                                     	if($sender->isOp()){
                                     	$sender->setHealth(20);
                                         $sender->setMaxHealth(20);
                                         $sender->sendMessage("§aYour life points has been fully §ehealed!");
                                          return true;
                                     }
                                   }
                                 }
                                   
                                     if(strtolower($command->getName()) == "feed") {
                                     if($sender->hasPermission("implactor.feed")){
                                     	if($sender->isOp()){
                                     	$sender->setFood(20);
                                         $sender->sendMessage("§aYour hunger bar has been fully §efilled!");
                                         return true;
                                     }
                                  }
                                }
                                
                                    if(strtolower($command->getName()) == "ihelp") {
                                     if($sender->hasPermission("implactor.command.help")){
                                            $sender->sendMessage("§b--( §eImplactor §aHelp §b)--");
                                            $sender->sendMessage("§e/ihelp §9- §fImplactor Command List!");
                                            $sender->sendMessage("§e/iabout §9- §fAbout Implactor plugin!");
                                            $sender->sendMessage("§e/ping §9- §fPong?");
                                            $sender->sendMessage("§e/feed §9- §dFeed yourself when on hunger!");
                                            $sender->sendMessage("§e/heal §9- §fHeal yourself when on emergency!");
                                            $sender->sendMessage("§e/gms §9- §fChange your gamemode to §cSurvival §fmode!");
                                            $sender->sendMessage("§e/gmc §9- §fChange your gamemode to §aCreative §fmode!");
                                            $sender->sendMessage("§e/gma §9- §fChange your gamemode to §cAdventure §fmode!");
                                            $sender->sendMessage("§e/gmspc §9- §fChange your gamemode to §bSpectator §fmode!");
                                            $sender->sendMessage("§e/hub §9- §fTeleport/Return To Hub!");
                                            $sender->sendMessage("§e/sethub §9- §fSet the main hub location point!");
                                            $sender->sendMessage("§e/fly §9- §fTurn on/off the fly ability!");
                                            $sender->sendMessage("§e/kill §9- §fKill yourself!");
                                            $sender->sendMessage("§e/wild §9- §fTeleport to the wild spot!");
                                            $sender->sendMessage("§e/clearitem §9- §fClear your items from your inventory!");
                                            $sender->sendMessage("§e/cleararmor §9- §fClear your armor from your body!");
                                            $sender->sendMessage("§e/clearall §9- §fClear all items/armors from your inventory and body!");
                                            $sender->sendMessage("§e/nick §9- §fSet your nickname or default!"); 
                                            return true;
                                           }
                                         }                                             
                                      
                                           if(strtolower($command->getName()) == "iabout") {
                                           if($sender->hasPermission("implactor.command.about")){
                                             $sender->sendMessage("§b--§a[§dImplactor §a| §bAbout §a]§b--");
                                             $sender->sendMessage("§aA plugin with having some features!");
                                             $sender->sendMessage("\§eMade by Zadezter \n §fCreated on §c23 May 2018");
                                             return true;
                                             }
                                           }
                                         }
                                       }
                                
                                     
                               
    
