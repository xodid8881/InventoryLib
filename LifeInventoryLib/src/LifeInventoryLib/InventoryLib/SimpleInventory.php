<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

declare(strict_types=1);

namespace LifeInventoryLib\InventoryLib;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\inventory\BaseInventory;
/**
 * This class provides a complete implementation of a regular inventory.
 */
class SimpleInventory extends BaseInventory{
	/**
	 * @var \SplFixedArray|(Item|null)[]
	 * @phpstan-var \SplFixedArray<Item|null>
	 */
	protected \SplFixedArray $slots;

	public function __construct(int $size){
		$this->slots = new \SplFixedArray($size);
		parent::__construct();
	}

	/**
	 * Returns the size of the inventory.
	 */
	public function getSize() : int{
		return $this->slots->getSize();
	}

	public function getItem(int $index) : Item{
		return $this->slots[$index] !== null ? clone $this->slots[$index] : ItemFactory::air();
	}

	/**
	 * @return Item[]
	 */
	public function getContents(bool $includeEmpty = false) : array{
		$contents = [];

		foreach($this->slots as $i => $slot){
			if($slot !== null){
				$contents[$i] = clone $slot;
			}elseif($includeEmpty){
				$contents[$i] = ItemFactory::air();
			}
		}

		return $contents;
	}

	protected function internalSetContents(array $items) : void{
		for($i = 0, $size = $this->getSize(); $i < $size; ++$i){
			if(!isset($items[$i])){
				$this->clear($i);
			}else{
				$this->setItem($i, $items[$i]);
			}
		}
	}

	protected function internalSetItem(int $index, Item $item) : void{
		$this->slots[$index] = $item->isNull() ? null : $item;
	}
}
