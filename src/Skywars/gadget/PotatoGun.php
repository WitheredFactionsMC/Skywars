<?php

namespace Skywars\gadget;

use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat;
use Skywars\player\CustomPlayer;
use Skywars\Skywars;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\FloatTag;

/**
 * Fires potato gun
 *
 */
class PotatoGun implements Listener {

	const PRODUCT_ID = 13;
	/** @var Skywars */
	private $plugin;

	/**
	 * Base class constructor, set as event listener
	 * 
	 * @param Skywars $plugin
	 */
	public function __construct(Skywars $plugin) {
		$this->plugin = $plugin;
		$plugin->getServer()->getPluginManager()->registerEvents($this, $plugin);
	}

	/**
	 * Calls when player interact with potato - allow him to shoot 
	 * 
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onInteract(PlayerInteractEvent $event) {
		if ($event->getFace() === 0xff) {
			$player = $event->getPlayer();
			if ($player->currentGame !== null) {
				return;
			}
			if (!$player->isAuthorized()) {
				$event->getPlayer()->sendTip($event->getPlayer()->getTranslatedString("NEEDS_LOGIN"));
				return;
			}
			//check if we have potato item
			if ($event->getItem()->getID() === 392) {
				if ($player->getProductAmount(self::PRODUCT_ID) > 0) {
					//check for cooldown
					if (!$player->cooldown(CustomPlayer::COOLDOWN_LOBBY, "gadget_potato", 30, TextFormat::GOLD . "Potato")) {
						return;
					}
					//remove used potato
					$player->removeProduct(self::PRODUCT_ID, 1);
				} else {
					GadgetManager::buyGadget($player, self::PRODUCT_ID, 50);

					return;
				}

				$this->launchPotato($player, $event->getTouchVector());
			}
		}
	}

	/**
	 * Describe potato shoot math
	 * 
	 * @param Player $player
	 * @param Vector3 $vec
	 */
	public function launchPotato(Player $player, Vector3 $vec) {
		$nbt = new Compound("", [
			"Pos" => new Enum("Pos", [
				new DoubleTag("", $player->x),
				new DoubleTag("", $player->y + $player->getEyeHeight()),
				new DoubleTag("", $player->z)
					]),
			"Motion" => new Enum("Motion", [
				new DoubleTag("", -sin($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI)),
				new DoubleTag("", -sin($player->pitch / 180 * M_PI)),
				new DoubleTag("", cos($player->yaw / 180 * M_PI) * cos($player->pitch / 180 * M_PI))
					]),
			"Rotation" => new Enum("Rotation", [
				new FloatTag("", $player->yaw),
				new FloatTag("", $player->pitch)
					]),
		]);

		$f = 1.5;
		$entity = new PotatoProjectile($player->chunk, $nbt, $player);
		$entity->setMotion($entity->getMotion()->multiply($f));
		$entity->spawnToAll();
	}

	/**
	 * Calls when somebody has been shoot by potato
	 * 
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event) {
		if ($event instanceof EntityDamageByChildEntityEvent) {
			if ($event->getChild() instanceof PotatoProjectile) {
				$shooter = $event->getDamager();
				$player = $event->getEntity();
				//send messages to shooter and victim
				if ($shooter instanceof Player && $player instanceof Player) {					
					$shooter->sendTip(TextFormat::YELLOW . $shooter->getTranslatedString("SEND_POTATO") 
							. TextFormat::BOLD . $player->getName() . TextFormat::RESET . TextFormat::YELLOW . "!");
					$player->sendTip(TextFormat::YELLOW . $shooter->getTranslatedString("GET_POTATO") 
							. TextFormat::BOLD . $shooter->getName() . TextFormat::RESET . TextFormat::YELLOW . "!");
				} else {
					$event->setCancelled(true);
				}
			}
		}
	}

}
