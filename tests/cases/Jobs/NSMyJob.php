<?php

namespace Example;

use MediaWiki\JobQueue\Job;
use MediaWiki\MediaWikiServices;

class NSMyJob extends Job {

	public function __construct( $title, $params = [] ) {
		parent::__construct( 'example', $title, $params );
	}

	public function run() {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $this );
	}

}
