<?php

namespace Example;

use MediaWiki\Api\ApiBase;
use MediaWiki\MediaWikiServices;

class NSNormalApi extends ApiBase {

	public function test1( $par ) {
		// getService() with arbitrary service name
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $par );
	}

}
