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
* A plugin with some features for Broken Edition!
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
use pocketmine\event\player\PlayerToggleFlightEvent;
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
         $this->getServer()->getScheduler()->scheduleRepeatingTask(new HubParticle($this), 20);
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
          $player->kill();
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
                          $sender->sendMessage(IR::GRAY. "----------" .IR::WHITE. "\n Returning to hub..." .IR::GRAY. "\n----------");
                          return true;
                     }
                 }
                 
                       if(strtolower($command->getName()) == "sethub") {
                       	if($sender->hasPermission("implactor.sethub")) {
                       	   if($sender->isOp()){
                       	  $sender->getLevel()->setSpawnLocation($sender);
                             $sender->getServer()->setDefaultLevel($sender->getLevel());
                             $sender->sendMessage(IR::YELLOW . "Set the main hub successfully!");
                             return true;
                         }
                      }
                   }
                        
                        if(strtolower($command->getName()) == "fly") {
                       	if($sender->hasPermission("implactor.fly")) {
                       	   if($sender->isOp()){
                                if(!$sender->getAllowFlight()){
                                 $sender->setAllowFlight(true);
                                 $sender->sendMessage("§8§l(§a!§8)§r §7Your fly ability has been §l§aENABLED§r§7!");
                              }else{
                                 $sender->setAllowFlight(false);
                                 $sender->setFlying(false);
                                 $sender->sendMessage("§8§l(§c!§8)§r §7Your fly ability has been §l§cDISABLED§r§7!");
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
                           $sender->sendMessage("§eChanged your gamemode to §bCreative!");
                           return true;
                       }
                     }
                   }
                     
                           if(strtolower($command->getName()) == "gms") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                              $sender->setGamemode(Player::SURVIVAL); 
                              $sender->sendMessage("§eChanged your gamemode to §bSurvival!");
                              return true;
                       }
                     }
                   }
                           if(strtolower($command->getName()) == "gma") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                       	   $sender->setGamemode(Player::ADVENTURE);
                           $sender->sendMessage("§eChanged your gamemode to §bAdventure!");
                           return true;
                        }
                      }
                    }
                       
                           if(strtolower($command->getName()) == "gmspc") {
                       	if($sender->hasPermission("implactor.gamemode")) {
                       	   if($sender->isOp()){
                              $sender->setGamemode(Player::SPECTATOR);
                              $sender->sendMessage("§eChanged your gamemode to §bSpectator!");
                              return true;
                              }
                            }
                          }
                        }
                     }
            
