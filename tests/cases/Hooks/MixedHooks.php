<?php

use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;
use MediaWiki\MediaWikiServices;
use MediaWiki\Output\Hook\BeforePageDisplayHook;

class MixedHooks implements BeforePageDisplayHook, LoadExtensionSchemaUpdatesHook {

	public function onBeforePageDisplay( $out, $skin ): void {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $out, $skin );
	}

	public function onLoadExtensionSchemaUpdates( $updater ) {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $updater );
	}

}
