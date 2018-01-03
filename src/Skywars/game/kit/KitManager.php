<?php

namespace Skywars\game\kit;

use pocketmine\utils\TextFormat;
use Skywars\Skywars;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

/**
 * Handle giving kits
 */
class KitManager {

	public static function enable(Skywars $plugin) {
		/** @var array - contains descriptions of kit statues*/
		$kits = array(
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Classic SG Vip+",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -12,
				"yaw" => 90,
				"item" => Item::DIAMOND_AXE,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Warrior",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -10,
				"yaw" => 90,
				"item" => Item::IRON_AXE,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Sentry",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -8,
				"yaw" => 90,
				"item" => Item::IRON_SWORD,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Midas",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -6,
				"yaw" => 90,
				"item" => Item::GOLD_INGOT,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Archer",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -4,
				"yaw" => 90,
				"item" => Item::BOW,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Teleporter",
				"posX" => 3.5,
				"posY" => 96,
				"posZ" => -2,
				"yaw" => 90,
				"item" => Item::COMPASS,
				"color" => 14,
				"boots" => Item::GOLD_BOOTS,
				"leggings" => Item::GOLD_LEGGINGS,
				"chestplate" => Item::GOLD_CHESTPLATE,
				"helmet" => Item::GOLD_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Brawler",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -12,
				"yaw" => 270,
				"item" => Item::RED_MUSHROOM,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Athlete",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -10,
				"yaw" => 270,
				"item" => Item::APPLE,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Prospector",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -8,
				"yaw" => 270,
				"item" => Item::DIAMOND_PICKAXE,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Lumberman",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -6,
				"yaw" => 270,
				"item" => Item::WOODEN_PLANK,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Creeper",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -4,
				"yaw" => 270,
				"item" => Item::TNT,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
			array(
				"prefix" => TextFormat::GOLD,
				"name" => "Explorer",
				"posX" => -2,
				"posY" => 96,
				"posZ" => -2,
				"yaw" => 270,
				"item" => Item::CHEST,
				"color" => 14,
				"boots" => Item::DIAMOND_BOOTS,
				"leggings" => Item::DIAMOND_LEGGINGS,
				"chestplate" => Item::DIAMOND_CHESTPLATE,
				"helmet" => Item::DIAMOND_HELMET
			),
		);

		//set statues on carpets in lobby
		$level = $plugin->level;
		foreach ($kits as $kit) {
			$pos = new Vector3($kit["posX"], $kit["posY"], $kit["posZ"]);
			$block = Block::get(Block::CARPET, $kit["color"]);
			$level->setBlock($pos, $block);
			$level->setBlock($pos->add(0, 0, -1), $block);
			$level->setBlock($pos->add(1, 0, 0), $block);
			$level->setBlock($pos->add(1, 0, -1), $block);
			$level->setBlock($pos->add(-1, 0, 0), $block);
			$level->setBlock($pos->add(-1, 0, -1), $block);
			$plugin->npcManager->addNPC($kit["prefix"] . "[Kit] " . $kit["name"] . "\n", $kit["posX"], $kit["posY"], $kit["posZ"], $kit["yaw"], 0, $kit["item"], $kit["boots"], $kit["leggings"], $kit["chestplate"], $kit["helmet"], $kit["name"]);
		}
	}

}
