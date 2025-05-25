<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler;

class MyHandler extends Handler {

	public function execute() {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( 123 );
	}

}
