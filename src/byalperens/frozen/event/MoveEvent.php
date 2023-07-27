<?php

namespace byalperens\frozen\event;

use byalperens\frozen\Frozen;
use byalperens\frozen\utils\FrozenScreen;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\world\World;
use pocketmine\utils\TextFormat as C;

class MoveEvent implements Listener{

    private array $biomePlayer = [];

    /**
     * @param PlayerMoveEvent $event
     * @return void
     */
    public function onPlayerMove(PlayerMoveEvent $event): void{
        $player = $event->getPlayer();
        $config = Frozen::getInstance()->getPluginConfig();
        $biomes = $config->get("biomes");
        $position = $player->getPosition();
        $biome = $player->getWorld()->getBiomeId($position->getFloorX(), $position->getFloorY(), $position->getFloorZ());

        if (in_array($biome, $biomes)){
            if ($player->getWorld()->getTime() >= World::TIME_NIGHT && $player->getWorld()->getTime() <= World::TIME_SUNRISE){
                $delay = (int) $config->get("frozen-attack-delay");

                if (!isset($this->biomePlayer[spl_object_hash($player)])){
                    FrozenScreen::screen($player);
                    $player->getEffects()->add(new EffectInstance(VanillaEffects::SLOWNESS(), 999999));
                    $player->sendTip(C::colorize($config->get("frozen-message")));
                    $this->biomePlayer[spl_object_hash($player)] = time() + $delay;
                }else{
                    if (time() - $this->biomePlayer[spl_object_hash($player)] == $delay){
                        $player->attack(new EntityDamageEvent($player, EntityDamageEvent::CAUSE_CUSTOM, (int) $config->get("frozen-attack")));
                        $this->biomePlayer[spl_object_hash($player)] = time();
                    }
                }
            }else{
                FrozenScreen::close($player);
                $player->getEffects()->remove(VanillaEffects::SLOWNESS());
                unset($this->biomePlayer[spl_object_hash($player)]);
            }
        }else{
            if (isset($this->biomePlayer[spl_object_hash($player)])){
                FrozenScreen::close($player);
                $player->getEffects()->remove(VanillaEffects::SLOWNESS());
                unset($this->biomePlayer[spl_object_hash($player)]);
            }
        }
    }
}
