<?php

use MediaWiki\MediaWikiServices;

class NormalApi extends ApiBase {

    public function test1( $par ) {
        // getService() with arbitrary service name
        $service = MediaWikiServices::getInstance()->getService( 'MyService' );
        $service->run( $par );
    }

}