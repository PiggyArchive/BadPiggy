<?php

namespace BadPiggy\Sounds;

use pocketmine\level\sound\GenericSound;
use pocketmine\math\Vector3;

class TNTPrimeSound extends GenericSound{
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, 1005, $pitch);
	}
	
}