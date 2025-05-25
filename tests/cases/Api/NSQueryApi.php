<?php

namespace Example;

use MediaWiki\Api\ApiQueryBase;
use MediaWiki\MediaWikiServices;

class NSQueryApi extends ApiQueryBase {

	public function test1( $par ) {
		// getService() with arbitrary service name
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $par );
	}

}
