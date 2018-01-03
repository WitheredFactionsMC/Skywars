<?php

namespace Skywars\particle\effects;

use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\PortalParticle;
use Skywars\player\CustomPlayer;

/**
 * Class for showing portal-to-game effects
 */
class PortalParticleEffect implements ParticleEffect {

	const PRODUCT_ID = 9;
	/**
	 * Overwrite as not selectable
	 * @param CustomPlayer $player
	 */
	public function select(CustomPlayer $player) {
		//
	}

	/**
	 * Repeatable method to show particle effects to all players in lobby
	 * 
	 * @param $currentTick
	 * @param CustomPlayer $player
	 * @param array|null $showTo
	 */
	public function tick($currentTick, CustomPlayer $player, $showTo) {
		$player->getLevel()->addParticle(new DustParticle($player->add(-0.5 + lcg_value(), 1.5 + lcg_value() / 2, -0.5 + lcg_value()), 255, 0, 255), $showTo);
		$player->getLevel()->addParticle(new DustParticle($player->add(-0.5 + lcg_value(), 1.5 + lcg_value() / 2, -0.5 + lcg_value()), 255, 0, 255), $showTo);
		$player->getLevel()->addParticle(new PortalParticle($player->add(-0.5 + lcg_value(), 0.5 + lcg_value(), -0.5 + lcg_value())), $showTo);
		$player->getLevel()->addParticle(new PortalParticle($player->add(-0.5 + lcg_value(), 0.5 + lcg_value(), -0.5 + lcg_value())), $showTo);
	}

}
