<?php

namespace WikiTeq\MediaWikiServicesCheckPlugin\Tests;

use DirectoryIterator;
use PHPUnit\Framework\TestCase;

/**
 * @covers \WikiTeq\MediaWikiServicesCheckPlugin\MediaWikiServicesCheckPlugin
 */
class PluginTest extends TestCase {

	/** @dataProvider provideTestCases */
	public function testScenarios( $testCaseDir, $expectedIssues ) {
		$testDirPath = realpath( './tests' );
		// $this->assertSame( $testDirPath, '' );
		// Go back to the main directory
		chdir( __DIR__ . '/../' );
		// Build the command to run
		$cmd = "php vendor/phan/phan/phan" .
			" --allow-polyfill-parser" .
			" -d \"$testDirPath\"" .
			" -k \"test-config.php\"" .
			" -l \"stubs\"" .
			" -l \"cases/$testCaseDir\"";
		// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.shell_exec
		$phanOutput = shell_exec( $cmd ) ?? '';
		// Trim to avoid issues with newlines
		$this->assertSame( trim( $expectedIssues ), trim( $phanOutput ) );
	}

	public function provideTestCases() {
		$dirIter = new DirectoryIterator( __DIR__ . '/cases' );
		foreach ( $dirIter as $directory ) {
			if ( !$directory->isDot() ) {
				$folder = $directory->getPathname();
				// In `/cases` we have a bunch of sub directories, each with
				// PHP files to analyze and then `expected.txt` with the results
				$testName = basename( $folder );
				$expected = file_get_contents( $folder . '/expected.txt' );
				yield $testName => [ $testName, $expected ];
			}
		}
	}
}
