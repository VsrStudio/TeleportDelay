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
This plugin provides the following commands:

### `/tpd help`
- **Description**: Displays a list of available commands.
- **Usage**: `/tpd help`

### `/tpd create`
- **Description**: Creates a new teleportation point.
- **Usage**: `/tpd create`

### `/tpd delete`
- **Description**: Deletes an existing teleportation point.
- **Usage**: `/tpd delete`

### `/tpd setteleport`
- **Description**: Sets the teleportation location.
- **Usage**: `/tpd setteleport`

### `/tpd list`
- **Description**: Lists all available teleportation points.
- **Usage**: `/tpd list`

### `/tpd setglobaldelay`
- **Description**: Sets a global teleport delay.
- **Usage**: `/tpd setglobaldelay`

### `/tpd tp`
- **Description**: Teleports with the specified delay.
- **Usage**: `/tpd tp`

### `/rtp` (Random Teleport)
- **Description**: Performs a random teleport for the player.
- **Usage**: `/rtp`
- **Permissions**: `teleportdelay.rtp`
