<?php

namespace VsrStudio\TeleportDelay;

use pocketmine\plugin\PluginBase;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use VsrStudio\TeleportDelay\TeleportTask;
use pocketmine\math\Vector3;

class Main extends PluginBase {

    private $teleportDelays = [];
    private $globalDelay = 0;

    public function onEnable(): void {
    $this->saveDefaultConfig();
    $this->getLogger()->info("TeleportDelay plugin enabled");

    $map = $this->getDescription()->getAuthors();
    $ver = $this->getDescription()->getVersion();

    if (isset($map[0])) {
        if ($map[0] !== "VsrStudio" or $ver !== "1.0.0") {
            $this->getLogger()->emergency("§cPlugin info has been changed, please give the author the proper credits. Set the author to \"VsrStudio\" and the version to \"1.0.0\".");
            $this->getServer()->shutdown();
        }
    }
}

public function onDisable(): void {
    $this->getLogger()->info("TeleportDelay plugin disabled");
}

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
    if (!$sender instanceof Player) {
        $sender->sendMessage(TextFormat::RED . "This command can only be used by players.");
        return false;
    }

    switch ($command->getName()) {
        case "tpd":
            if (count($args) < 1) {
                $sender->sendMessage(TextFormat::RED . "Usage: /tpd help");
                return false;
            }

            $action = $args[0];

            switch ($action) {
                case "create":
                    if (count($args) < 3) {
                        $sender->sendMessage(TextFormat::RED . "Usage: /tpd create <name> <delay>");
                        return false;
                    }
                    $name = $args[1];
                    $delay = $args[2];
                    if (!is_numeric($delay) || $delay <= 0) {
                        $sender->sendMessage(TextFormat::RED . "Delay must be a positive number.");
                        return false;
                    }
                    $this->createTeleportDelay($sender, $name, (int)$delay);
                    return true;

                case "delete":
                    if (count($args) < 2) {
                        $sender->sendMessage(TextFormat::RED . "Usage: /tpd delete <name>");
                        return false;
                    }
                    $name = $args[1];
                    $this->deleteTeleportDelay($name);
                    return true;

                case "setteleport":
                    if (count($args) < 2) {
                        $sender->sendMessage(TextFormat::RED . "Usage: /tpd setteleport <name>");
                        return false;
                    }
                    $name = $args[1];
                    $this->setTeleportPosition($sender, $name);
                    return true;

                case "setglobaldelay":
                    if (count($args) < 2) {
                        $sender->sendMessage(TextFormat::RED . "Usage: /tpd setglobaldelay <delay>");
                        return false;
                    }
                    $delay = $args[1];
                    if (!is_numeric($delay) || $delay < 0) {
                        $sender->sendMessage(TextFormat::RED . "Delay must be a positive number.");
                        return false;
                    }
                    $this->setGlobalTeleportDelay((int)$delay);
                    $sender->sendMessage(TextFormat::GREEN . "Global teleport delay set to $delay seconds.");
                    return true;

                case "list":
                    $this->listTeleportDelays($sender);
                    return true;

                case "tp":
                    if (count($args) < 2) {
                        $sender->sendMessage(TextFormat::RED . "Usage: /tpd tp <name>");
                        return false;
                    }
                    $name = $args[1];
                    $this->teleportPlayer($sender, $name);
                    return true;

                case "help":
                    $this->getHelp($sender);
                    return true;

                default:
                    $sender->sendMessage(TextFormat::RED . "Invalid command. Use /tpd help for available commands.");
                    return false;
            }
            break;

        case "rtp":
            if (!$sender->hasPermission("teleportdelay.rtp")) {
                $sender->sendMessage(TextFormat::RED . "You do not have permission to use this command.");
                return false;
            }
            $this->randomTeleport($sender);
            return true;
    }

    return false;
}

public function getHelp(Player $p) {
    $title = "§9-----[ §6TeleportDelay §9]-----";
    $p->sendMessage($title);
    $p->sendMessage("§9- §a/tpd help §7- §gShow Help Command");
    $p->sendMessage("§9- §a/tpd create §7- §gCreate Teleport");
    $p->sendMessage("§9- §a/tpd delete §7- §gDelete Teleport");
    $p->sendMessage("§9- §a/tpd setteleport §7- §gSet Teleport");
    $p->sendMessage("§9- §a/tpd list §7- §gList Teleport");
    $p->sendMessage("§9- §a/tpd setglobaldelay §7- §gSet global delay");
    $p->sendMessage("§9- §a/tpd tp §7- §gTeleportDelay");
    $p->sendMessage("§9- §a/rtp §7- §gRandom Teleport");
}

    private function createTeleportDelay(Player $player, string $name, int $delay): void {
        if (isset($this->teleportDelays[$name])) {
            $player->sendMessage(TextFormat::RED . "A teleport delay with that name already exists.");
            return;
        }
        $this->teleportDelays[$name] = [
            'delay' => $delay,
            'player' => $player,
            'position' => $player->getPosition(),
        ];
        $player->sendMessage(TextFormat::GREEN . "Teleport delay '$name' created with a delay of $delay seconds.");
    }

    private function deleteTeleportDelay(string $name): void {
    if (!isset($this->teleportDelays[$name])) {
        $this->getServer()->getPlayerExact($name)?->sendMessage(TextFormat::RED . "No teleport delay found with that name.");
        return;
    }
    unset($this->teleportDelays[$name]);
    $this->getServer()->getPlayerExact($name)?->sendMessage(TextFormat::GREEN . "Teleport delay '$name' deleted.");
}

    private function setTeleportPosition(Player $player, string $name): void {
        if (!isset($this->teleportDelays[$name])) {
            $player->sendMessage(TextFormat::RED . "No teleport delay found with that name.");
            return;
        }
        $this->teleportDelays[$name]['position'] = $player->getPosition();
        $player->sendMessage(TextFormat::GREEN . "Teleport position for '$name' set to your current location.");
    }

    private function teleportPlayer(Player $player, string $name): void {
    if (!isset($this->teleportDelays[$name])) {
        $player->sendMessage(TextFormat::RED . "No teleport delay found with that name.");
        return;
    }

    $delay = $this->teleportDelays[$name]['delay'] ?? $this->globalDelay;
    $position = $this->teleportDelays[$name]['position'];

    $player->sendMessage(TextFormat::YELLOW . "Teleporting in $delay seconds...");

    $task = new TeleportTask($player, $position);
    $this->getScheduler()->scheduleDelayedTask($task, $delay * 20);
}

    private function listTeleportDelays(Player $player): void {
        if (empty($this->teleportDelays)) {
            $player->sendMessage(TextFormat::RED . "No teleport delays have been created yet.");
            return;
        }

        $player->sendMessage(TextFormat::GREEN . "List of teleport delays:");
        foreach ($this->teleportDelays as $name => $data) {
            $player->sendMessage(TextFormat::AQUA . "Name: $name | Delay: " . $data['delay'] . " seconds | Position: X: " . $data['position']->getX() . " Y: " . $data['position']->getY() . " Z: " . $data['position']->getZ());
        }
    }

    private function setGlobalTeleportDelay(int $delay): void {
        $this->globalDelay = $delay;
    }

private function randomTeleport(Player $player): void {
    $world = $player->getWorld();

    $minX = $this->getConfig()->getNested("teleport-bounds.min-x", -1000);
    $maxX = $this->getConfig()->getNested("teleport-bounds.max-x", 1000);
    $minZ = $this->getConfig()->getNested("teleport-bounds.min-z", -1000);
    $maxZ = $this->getConfig()->getNested("teleport-bounds.max-z", 1000);
    $delay = $this->getConfig()->get("teleport-delay", 5); // delay dalam detik

    $player->sendMessage(TextFormat::YELLOW . "Teleporting in $delay seconds. Do not move!");

    $this->getScheduler()->scheduleDelayedTask(new class($this, $player, $world, $minX, $maxX, $minZ, $maxZ) extends Task {
        private $plugin;
        private $player;
        private $world;
        private $minX;
        private $maxX;
        private $minZ;
        private $maxZ;

        public function __construct($plugin, Player $player, $world, $minX, $maxX, $minZ, $maxZ) {
            $this->plugin = $plugin;
            $this->player = $player;
            $this->world = $world;
            $this->minX = $minX;
            $this->maxX = $maxX;
            $this->minZ = $minZ;
            $this->maxZ = $maxZ;
        }

        public function onRun(): void {
            $attempts = 0;
            $maxAttempts = 5;

            while ($attempts < $maxAttempts) {
                $randomX = mt_rand($this->minX, $this->maxX);
                $randomZ = mt_rand($this->minZ, $this->maxZ);

                $chunkX = $randomX >> 4;
                $chunkZ = $randomZ >> 4;

                $this->world->loadChunk($chunkX, $chunkZ);

                if ($this->world->isChunkLoaded($chunkX, $chunkZ)) {
                    $randomY = $this->world->getHighestBlockAt($randomX, $randomZ) + 1;
                    $this->player->teleport(new Vector3($randomX, $randomY, $randomZ));
                    $this->player->sendMessage(TextFormat::GREEN . "You have been randomly teleported!");
                    return;
                }

                $attempts++;
            }

            $this->player->sendMessage(TextFormat::RED . "Failed to load a safe location for teleportation after multiple attempts.");
        }
    }, $delay * 20);
}
}
