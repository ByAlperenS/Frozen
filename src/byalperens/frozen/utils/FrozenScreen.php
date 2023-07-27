<?php

namespace byalperens\frozen\utils;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class FrozenScreen{

    /** @var string */
    private const FROZEN_SCREEN_SOUND = "ambient.frozen";

    /**
     * @param Player $player
     * @param bool $sound
     * @return void
     */
    public static function screen(Player $player, bool $sound = true): void{
        $player->sendTitle("frozen_screen");

        if ($sound){
            $position = $player->getPosition();
            $packet = PlaySoundPacket::create(self::FROZEN_SCREEN_SOUND, $position->getX(), $position->getY(), $position->getZ(), 1, 1);
            $player->getNetworkSession()->sendDataPacket($packet);
        }
    }

    /**
     * @param Player $player
     * @return void
     */
    public static function close(Player $player): void{
        $player->removeTitles();
    }
}
