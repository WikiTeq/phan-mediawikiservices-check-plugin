<?php

namespace Example;

use MediaWiki\Content\ContentHandler;
use MediaWiki\MediaWikiServices;

class NSMyHandler extends ContentHandler {

	public function __construct() {
		parent::__construct( 'text', [ 'text/plain' ] );
	}

	public function run() {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $this );
	}

}
