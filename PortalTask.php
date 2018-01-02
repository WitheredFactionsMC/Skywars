<?php

namespace Skywars;

use pocketmine\level\Level;
use pocketmine\level\particle\DustParticle;
use pocketmine\level\particle\EnchantmentTableParticle;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;
use pocketmine\Player;
use Skywars\particle\effects\RainbowParticleEffect;

/**
 * Handle main lobby portal which teleports players in game
 */
class PortalTask extends PluginTask {
	/** @var Level */
	public $level;
	/** @var Vector3 */
	public $pos;
	/** @var array */
	private $players = [];

	/**
	 * Class constructor, save current level and position to create portal
	 * 
	 * @param \Skywars\Skywars $plugin
	 * @param Level $level
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 */
	public function __construct(Skywars $plugin, Level $level, $x, $y, $z) {
		parent::__construct($plugin);
		$this->level = $level;
		$this->pos = new Vector3($x, $y - 0.5, $z);
	}

	/**
	 * Add player to teleporting players list
	 * 
	 * @param Player $player
	 * @return void
	 */
	public function spawnToPlayer(Player $player) {
		if (array_search($player, $this->players) !== false) {
			return;
		}
		array_push($this->players, $player);
	}

	/**
	 * Remove player from teleporting players list
	 * 
	 * @param Player $player
	 */
	public function despawnFromPlayer(Player $player) {
		if (($key = array_search($player, $this->players)) !== false) {
			unset($this->players[$key]);
		}
	}

	private $i;

	/**
	 * Base repeatable method, called from Skywars class,
	 * add rainbow exxect to portal and dust particles to teleporting players
	 * 
	 * @param $currentTick
	 */
	public function onRun($currentTick) {
		
		$n = $this->i++;
		RainbowParticleEffect::hsv2rgb($n * 2, 100, 100, $r, $g, $b);

		$v = 2 * M_PI / 120 * ($n % 120);
		$i = 2 * M_PI / 60 * ($n % 60);
		$x = cos($i) * 2;
		$y = cos($v) * 2;
		$z = sin($i) * 2;

		$this->level->addParticle(new DustParticle($this->pos->add($x, 2 - $y, -$z), $r, $g, $b), $this->players);
		$this->level->addParticle(new DustParticle($this->pos->add(-$x, 2 - $y, $z), $r, $g, $b), $this->players);
		$this->level->addParticle(new DustParticle($this->pos->add($x, $y, -$z), $r, $g, $b), $this->players);
		$this->level->addParticle(new DustParticle($this->pos->add(-$x, $y, $z), $r, $g, $b), $this->players);

		$r = lcg_value();
		$v = 2 * M_PI * lcg_value();
		$i = 2 * M_PI * lcg_value();
		$x = cos($i) * $r * 2;
		$y = cos($v) * $r * 0.5;
		$z = sin($i) * $r * 2;
		$this->level->addParticle(new DustParticle($this->pos->add($x, 2 - $y, -$z), $r, $g, $b), $this->players);

		for ($_ = 0; $_ < 2; $_++) {
			$r = lcg_value();
			$v = 2 * M_PI * lcg_value();
			$i = 2 * M_PI * lcg_value();
			$x = cos($i) * $r * 2;
			$y = cos($v) * $r * 2;
			$z = sin($i) * $r * 2;
			$this->level->addParticle(new EnchantmentTableParticle($this->pos->add($x, 2 - $y, $z)), $this->players);
		}
	}

}
