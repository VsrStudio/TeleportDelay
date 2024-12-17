<?php

namespace VsrStudio\TeleportDelay;

use pocketmine\scheduler\Task;
use pocketmine\player\Player;
use pocketmine\math\Vector3;

class TeleportTask extends Task {
    private Player $player;
    private Vector3 $position;

    public function __construct(Player $player, Vector3 $position) {
        $this->player = $player;
        $this->position = $position;
    }

    public function onRun(): void {
        $this->player->teleport($this->position);
        $this->player->sendMessage("You have been teleported.");
    }
}
