<?php

namespace MediaWiki\Output\Hook {

	interface BeforePageDisplayHook {

		public function onBeforePageDisplay( $out, $skin ): void;

	}

	class_alias( BeforePageDisplayHook::class, 'MediaWiki\Hook\BeforePageDisplayHook' );

}

namespace MediaWiki\Installer\Hook {

	interface LoadExtensionSchemaUpdatesHook {

		public function onLoadExtensionSchemaUpdates( $updater );
	}

}
