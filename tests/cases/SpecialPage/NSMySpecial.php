<?php

namespace Example;

use MediaWiki\MediaWikiServices;
use MediaWiki\SpecialPage\SpecialPage;

class NSMySpecial extends SpecialPage {

	public function test1( $par ) {
		// getService() with arbitrary service name
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $par );
	}

	public function test2( $par ) {
		// get() with arbitrary service name
		$service = MediaWikiServices::getInstance()->get( 'MyService' );
		$service->run( $par );
	}

	public function test3( $par ) {
		// service-specific getter
		$service = MediaWikiServices::getInstance()->getUserFactory();
		$service->run( $par );
	}

}
