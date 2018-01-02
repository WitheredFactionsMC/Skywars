<?php

namespace Skywars\gadget;

use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use Skywars\player\action\NamedItem;
use Skywars\player\CustomPlayer;

/**
 * Describes logic of compass teleport item
 */
class TeleportItem extends NamedItem {

	/**
	 * Calls NamedItem class constructor
	 */
	public function __construct() {
		parent::__construct(Item::COMPASS, 0, 1, TextFormat::GREEN . "Teleport");
	}

	/**
	 * Set compass as selected and teleport player,
	 * then remove compass from inventory
	 * 
	 * @param CustomPlayer $player
	 */
	public function selected(CustomPlayer $player) {
		parent::selected($player);

		try {
			$b = $player->getTargetBlock(16);
			if ($b !== null) {
				$v = $b->add(0.5, 2, 0.5);
				$l = $b->getLevel();
				if ($l->getBlock($b->add(-1, 0, 0))->getId() !== 0) {
					$v = $v->add(1, 0, 0);
				}
				if ($l->getBlock($b->add(1, 0, 0))->getId() !== 0) {
					$v = $v->add(-1, 0, 0);
				}
				if ($l->getBlock($b->add(0, 0, -1))->getId() !== 0) {
					$v = $v->add(0, 0, 1);
				}
				if ($l->getBlock($b->add(0, 0, 1))->getId() !== 0) {
					$v = $v->add(0, 0, -1);
				}
				$player->teleport($v);
			}
		} catch (\Exception $e) {
			
		}
		$player->getInventory()->setHeldItemIndex($player->getEmptyHotbarSlot());
	}

}
