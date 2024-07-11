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

		// Map of class to detect extending => placeholder for message
		// The *FIRST* matching message is used, to allow for more specific
		// messages
		$baseClassMap = [
			'\\SpecialPage' => 'Special pages',
			'\\ApiQueryBase' => 'API query modules',
			'\\ApiBase' => 'API modules',
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
			return;
		}
	}
}

// Plugins return an instance of themselves at the end of their definition files
return new MediaWikiServicesCheckPlugin();
