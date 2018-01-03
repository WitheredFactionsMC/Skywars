<?php

namespace Skywars\gadget;

use pocketmine\entity\Snowball;
use pocketmine\Player;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\item\Item;
use pocketmine\entity\Entity;

/**
 * The potato dropped item entity
 */
class PotatoProjectile extends Snowball {
	/** @var float */
	protected $gravity = 0.04;
	/** @var float */
	protected $drag = 0.02;

	/**
	 * Set spawn options for potato gun
	 * 
	 * @param Player $player
	 */
	public function spawnTo(Player $player) {
		$pk = new AddItemEntityPacket;
		$pk->eid = $this->getID();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->item = Item::get(Item::POTATO);
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$player->dataPacket($pk);

		Entity::spawnTo($player);
	}

}
