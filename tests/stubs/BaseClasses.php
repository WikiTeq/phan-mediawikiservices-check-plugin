<?php

// Stubs for testing

namespace MediaWiki\SpecialPage {

	class SpecialPage {

		public function __construct( $a, $b ) {
		}
	}

	// For confirming that sub-sub classes also work
	class FormSpecialPage extends SpecialPage {
	}

	class_alias( SpecialPage::class, 'SpecialPage' );
	class_alias( FormSpecialPage::class, 'FormSpecialPage' );

}

namespace MediaWiki\Api {

	class ApiBase {
	}

	class ApiQueryBase extends ApiBase {
	}

	class_alias( ApiBase::class, 'ApiBase' );
	class_alias( ApiQueryBase::class, 'ApiQueryBase' );

}

namespace MediaWiki\JobQueue {

	abstract class Job {

		public function __construct( ...$all ) {
		}

		abstract public function run();

	}

	class_alias( Job::class, 'Job' );

}

namespace MediaWiki\Content {

	class ContentHandler {

		public function __construct( $modelId, $formats ) {
		}

	}

	class_alias( ContentHandler::class, 'ContentHandler' );

}

namespace MediaWiki\Rest {

	abstract class Handler {

		abstract public function execute();

	}

}
