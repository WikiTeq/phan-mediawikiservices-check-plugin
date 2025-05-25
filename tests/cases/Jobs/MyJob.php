<?php

use MediaWiki\MediaWikiServices;

class MyJob extends Job {

	public function __construct( $title, $params = [] ) {
		parent::__construct( 'example', $title, $params );
	}

	public function run() {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $this );
	}

}
