<?php

use MediaWiki\MediaWikiServices;

class MyHandler extends ContentHandler {

	public function __construct() {
		parent::__construct( 'text', [ 'text/plain' ] );
	}

	public function run() {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $this );
	}

}
