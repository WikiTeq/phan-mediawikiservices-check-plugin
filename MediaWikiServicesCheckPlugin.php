<?php

declare( strict_types=1 );

namespace WikiTeq\MediaWikiServicesCheckPlugin;

use ast\Node;
use Phan\AST\UnionTypeVisitor;
use Phan\Language\Type;
use Phan\Language\UnionType;
use Phan\PluginV3;
use Phan\PluginV3\PluginAwarePostAnalysisVisitor;
use Phan\PluginV3\PostAnalyzeNodeCapability;

/**
 * Plugin to add our MediaWikiServicesVisitor that gets used for every AST node
 */
class MediaWikiServicesCheckPlugin extends PluginV3 implements
	PostAnalyzeNodeCapability
{

	public static function getPostAnalyzeNodeVisitorClassName(): string {
		return MediaWikiServicesVisitor::class;
	}
}

/**
 * Visitor that detects MediaWikiServices::getInstance()->getService() and
 * similar in places where services can (and should) be injected.
 */
// phpcs:ignore Generic.Files.OneObjectStructurePerFile.MultipleFound
class MediaWikiServicesVisitor extends PluginAwarePostAnalysisVisitor {

	public function visitMethodCall( Node $node ): void {
		$typeUnion = UnionTypeVisitor::unionTypeFromNode(
			$this->code_base,
			$this->context,
			$node->children['expr']
		);

		// Use UnionType to simplify comparison with the type of expr
		$mwServicesType = UnionType::fromFullyQualifiedPHPDocString(
			'\\MediaWiki\\MediaWikiServices'
		);
		$isMWServices = $mwServicesType->isEqualTo( $typeUnion );
		if ( !$isMWServices ) {
			return;
		}

		// Check that we are in a non-static method in a class that should
		// support dependency injection
		if ( !$this->context->isInClassScope()
			|| !$this->context->isInFunctionLikeScope()
		) {
			return;
		}

		$funcLike = $this->context->getFunctionLikeInScope( $this->code_base );
		if ( $funcLike->isStatic() ) {
			return;
		}

		$method = $node->children['method'];
		$methodStart = substr( $method, 0, 3 );
		if ( $methodStart !== 'get' && $methodStart !== 'has' ) {
			// Something more complicated like peeking, ignore
			return;
		}

		if ( $this->emitSubclassBasedWarning() ) {
			return;
		}
		if ( $this->checkHookHandler() ) {
			return;
		}
	}

	/**
	 * Emit a warning if the class can have dependencies injected based on
	 * being a special page, api module, or other component that supports DI.
	 * Returns whether a warning was emitted or not.
	 */
	private function emitSubclassBasedWarning(): bool {
		// Map of class to detect extending => placeholder for message
		// The *FIRST* matching message is used, to allow for more specific
		// messages
		$baseClassMap = [
			'\\MediaWiki\\SpecialPage\\SpecialPage' => 'Special pages',
			'\\MediaWiki\\Api\\ApiQueryBase' => 'API query modules',
			'\\MediaWiki\\Api\\ApiBase' => 'API modules',
			'\\MediaWiki\\JobQueue\\Job' => 'Job classes',
			'\\MediaWiki\\Content\\ContentHandler' => 'Content handlers',
			'\\MediaWiki\\Rest\\Handler' => 'REST handlers',
			// Legacy class aliases
			'\\SpecialPage' => 'Special pages',
			'\\ApiQueryBase' => 'API query modules',
			'\\ApiBase' => 'API modules',
			'\\Job' => 'Job classes',
			'\\ContentHandler' => 'Content handlers',
		];

		$scope = $this->context->getScope();
		$currentClass = $scope->getClassFQSEN()->asType();
		foreach ( $baseClassMap as $baseClass => $msg ) {
			$baseClassType = Type::fromFullyQualifiedString( $baseClass );
			if ( !$currentClass->isSubclassOf(
					$baseClassType,
					$this->code_base
			) ) {
				continue;
			}
			$this->emit(
				'MediaWikiServicesAccessed',
				'%s should have services injected with dependency injection',
				[ $msg ]
			);
			return true;
		}
		return false;
	}

	/**
	 * Emit a warning if the class can have dependencies injected as a hook
	 * handler that supports DI. Returns whether a warning was emitted or not.
	 */
	private function checkHookHandler(): bool {
		$scope = $this->context->getScope();

		$clazz = $this->code_base->getClassByFQSEN( $scope->getClassFQSEN() );
		$interfaceNames = array_map(
			static fn ( $interface ) => $interface->getName(),
			$clazz->getInterfaceFQSENList()
		);
		$hookInterfaces = array_filter(
			$interfaceNames,
			static fn ( $name ) => str_ends_with( $name, 'Hook' )
		);

		$noDIHooks = [
			'LoadExtensionSchemaUpdatesHook',
			'MediaWikiServicesHook',
		];
		$yesDIHooks = array_diff( $hookInterfaces, $noDIHooks );

		if ( $yesDIHooks === [] ) {
			return false;
		}

		$msg = 'Hook handlers should have services injected with dependency injection';
		$noDIHooks = array_intersect( $hookInterfaces, $noDIHooks );
		if ( $noDIHooks ) {
			$msg .= '; the hooks that do not support DI can be split to a separate handler';
		}
		$this->emit(
			'MediaWikiServicesAccessed',
			$msg
		);
		return true;
	}
}

// Plugins return an instance of themselves at the end of their definition files
return new MediaWikiServicesCheckPlugin();
