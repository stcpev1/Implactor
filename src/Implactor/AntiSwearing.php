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

namespace Implactor\anti;

use Implactor\MainIR;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Player;

class AntiSwearing implements Listener {

    /** @var Core */
    private $plugin;
    /** @var array */
    private $badwords;

    public function __construct(MainIR $plugin){
        $this->plugin = $plugin;
        $this->badwords = ["anal", "anus", "ass", "bastard", "bitch", "boob", "cock", "cum", "cunt", "dick", "dildo", "dyke", "fag", "faggot", "fuck", "fuk", "fk", "hoe", "tits", "whore", "handjob", "homo", "jizz", "cunt", "kike", "kunt", "muff", "nigger", "penis", "piss", "poop", "pussy", "queer", "rape", "semen", "sex", "sexy", "shit", "slut", "titties", "twat", "vagina", "vulva", "wank", "FUCK", "BITCH", "FAGGOT", "DICK", "CUNT", "ASS", "nigger", "nigga"];
    }

    public function onChat(PlayerChatEvent $ev) : void{
        $msg = $ev->getMessage();
        $player = $ev->getPlayer();
        if(!$player instanceof Player) return;
        if($player->hasPermission("implactor.anti")){
        }else{
            foreach($this->badwords as $badwords){
                if(strpos($msg, $badwords) !== false){
                    $player->sendMessage("§l§7(§c!§7)§r §cDo not swearing on this chat, idiot!");
                    $ev->setCancelled();
                    return;
                }
            }
        }
    }
}
