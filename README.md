# TeleportDelay

TeleportDelay is a PocketMine-MP plugin that allows players to teleport randomly with a configurable delay. This plugin provides a fun experience by adding a delay before teleportation occurs.

## Features
- Random teleportation within configurable coordinates defined in `config.yml`.
- Delay time before teleportation can be customized.
- Chat messages to notify players of the delay time.
- Can be enabled or disabled via commands.

## Requirements
- PocketMine-MP version 5.0.0 or higher.

## Installation
1. Download the plugin and place it in the `plugins/` folder of your PocketMine-MP server.
2. Restart your server.

## Configuration (config.yml)
After installing the plugin, open the `config.yml` file found in the `plugins/TeleportDelayOne/` folder and adjust the settings as needed.

## Warning
```yaml
Version: 1.0.0
Author: VsrStudio
# Don't Delete or Change It If You Don't Want Your Server to Die
```
### Example `config.yml`:
```yaml
# Random Teleport Distance
teleport-bounds:
  min-x: -1000
  max-x: 1000
  min-z: -1000
  max-z: 1000
# For Random Teleport Delay
teleport-delay: 5 # Delay in seconds before teleportation (e.g., 5 seconds)
```
## Commands

| Command                | Description                                 | Usage               |
|------------------------|---------------------------------------------|---------------------|
| `/tpd help`             | Displays a list of available commands.      | `/tpd help`         |
| `/tpd create`           | Creates a new teleportation point.          | `/tpd create`       |
| `/tpd delete`           | Deletes an existing teleportation point.    | `/tpd delete`       |
| `/tpd setteleport`      | Sets the teleportation location.            | `/tpd setteleport`  |
| `/tpd list`             | Lists all available teleportation points.   | `/tpd list`         |
| `/tpd setglobaldelay`   | Sets a global teleport delay.               | `/tpd setglobaldelay` |
| `/tpd tp`               | Teleports with the specified delay.         | `/tpd tp`           |
| `/rtp`                  | Performs a random teleport for the player.  | `/rtp`              |

## Video Stup
[Watch the video on YouTube](https://youtu.be/SotCuwNYzQM?si=JdWo3OeCfFovaI3E)
