<?php

use MediaWiki\Installer\Hook\LoadExtensionSchemaUpdatesHook;
use MediaWiki\MediaWikiServices;

class HookNoDI implements LoadExtensionSchemaUpdatesHook {

	public function onLoadExtensionSchemaUpdates( $updater ) {
		$service = MediaWikiServices::getInstance()->getService( 'MyService' );
		$service->run( $updater );
	}

}
