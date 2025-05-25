<?php

use MediaWiki\MediaWikiServices;
use MediaWiki\Output\Hook\BeforePageDisplayHook;

class JustDIHook implements BeforePageDisplayHook {

	public function onBeforePageDisplay( $out, $skin ): void {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $out, $skin );
	}

}
