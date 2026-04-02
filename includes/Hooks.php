<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 *
 * @file
 */
namespace MediaWiki\Extension\KolzchutAnalytics;

use MediaWiki\Extension\ArticleContentArea\ArticleContentArea;
use MediaWiki\Extension\ArticleType\ArticleType;
use MediaWiki\Extension\GoogleTagManager\Hooks\DataLayerSetupHook;
use MediaWiki\Output\Hook\MakeGlobalVariablesScriptHook;
use MediaWiki\Output\OutputPage;
use MediaWiki\Registration\ExtensionRegistry;
use Skin;

class Hooks implements
	DataLayerSetupHook,
	MakeGlobalVariablesScriptHook
{

	/**
	 * MakeGlobalVariablesScript hook handler.
	 *
	 * Variables added:
	 * * `wgVisibleCategories` - the non-hidden categories on the page
	 *
	 * @param array &$vars
	 * @param OutputPage $out
	 */
	public function onMakeGlobalVariablesScript( &$vars, $out ): void {
		$vars['wgVisibleCategories'] = $out->getCategories( 'normal' );
	}

	/**
	 * @inheritDoc
	 */
	public function onDataLayerSetup( OutputPage $output, Skin $skin, array &$dataLayer ): void {
		$title = $output->getTitle();
		$extensionRegistry = ExtensionRegistry::getInstance();

		if ( $extensionRegistry->isLoaded( 'ArticleContentArea' ) ) {
			$dataLayer['articleContentArea'] = ArticleContentArea::getArticleContentArea( $title );
		}
		if ( $extensionRegistry->isLoaded( 'ArticleType' ) ) {
			$articleType = ArticleType::getArticleType( $title );
			$dataLayer['articleType'] = $articleType;
			$dataLayer['articleTypeInHebrewPlural'] = ArticleType::getReadableArticleTypeFromCode(
				$articleType, 10, [ 'language' => 'he' ]
			);
		}
	}
}
