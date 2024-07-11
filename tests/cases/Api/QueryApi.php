<?php

use MediaWiki\MediaWikiServices;

class QueryApi extends ApiQueryBase {

    public function test1( $par ) {
        // getService() with arbitrary service name
        $service = MediaWikiServices::getInstance()->getService( 'MyService' );
        $service->run( $par );
    }

}