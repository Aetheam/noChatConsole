<?php

namespace royal\noChatConsole;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{
    private Config $config;

    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    public function onChat(PlayerChatEvent $event): void
    {
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $player->sendMessage($event->getFormatter()->format($event->getPlayer()->getDisplayName(), $event->getMessage()));

        }
        if ($this->config->get("active_logs")) {
            $this->putInLog($event->getPlayer()->getDisplayName() . ": " . $event->getMessage());
        }
        $event->cancel();
    }

    public function putInLog(string $data): void
    {
        file_put_contents($this->getDataFolder() . $this->config->get("file_name"), $this->addDate($data) . PHP_EOL, FILE_APPEND);
    }

    private function addDate($texte): string
    {
        $date = date("d/m/Y");
        $heure = date("h:iA");
        $resultat = $date . "-" . $heure . " | " . $texte;
        return $resultat;
    }
}