<?php

namespace byalperens\frozen;

use byalperens\frozen\event\MoveEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as C;

class Frozen extends PluginBase{

    private Config $config;

    private static self $instance;

    public function onLoad(): void{
        self::$instance = $this;
    }

    public function onEnable(): void{
        $this->getLogger()->info(C::GREEN . "Plugin Enable - ByAlperenS");
        $this->getServer()->getPluginManager()->registerEvents(new MoveEvent(), $this);
        $this->saveResource("config.yml");
        @mkdir($this->getDataFolder());
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
    }

    /**
     * @return Config
     */
    public function getPluginConfig(): Config{
        return $this->config;
    }

    public static function getInstance(): self{
        return self::$instance;
    }
}
