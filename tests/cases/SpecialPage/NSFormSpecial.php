<?php

namespace Example;

use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\FormSpecialPage;

class NSFormSpecial extends FormSpecialPage {

	public function test1( $par ) {
		// getService() with arbitrary service name
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $par );
	}

}
