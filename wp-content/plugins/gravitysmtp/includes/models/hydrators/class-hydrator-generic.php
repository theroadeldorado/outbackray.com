<?php

namespace Gravity_Forms\Gravity_SMTP\Models\Hydrators;

class Hydrator_Generic implements Hydrator {

	public function hydrate( $row ) {
		return $row;
	}

}