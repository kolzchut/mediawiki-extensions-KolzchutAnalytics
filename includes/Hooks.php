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

use ExtensionRegistry;
use MediaWiki\Extension\ArticleContentArea\ArticleContentArea;
use MediaWiki\Extension\ArticleType\ArticleType;
use MediaWiki\Extension\GoogleTagManager\Hooks\DataLayerSetupHook;
use OutputPage;
use Skin;

class Hooks
	implements DataLayerSetupHook {
	private static $normalCats = [];

	/**
	 * MakeGlobalVariablesScript hook handler.
	 *
	 * Variables added:
	 * * `wgVisibleCategories` - the non-hidden categories on the page
	 *
	 * @param array $vars
	 * @param OutputPage $out
	 */
	public static function onMakeGlobalVariablesScript( array &$vars, OutputPage $out ) {
		if ( is_array( self::$normalCats ) ) {
			$vars[ 'wgVisibleCategories' ] = self::$normalCats;
		}
	}
	/**
	 * OutputPageMakeCategoryLinks hook handler
	 *
	 * We don't want to log hidden categories...
	 * this is the only place where that distinction is available, so we get it here
	 *
	 * @param OutputPage &$out
	 * @param array $categories
	 * @param array &$links
	 */
	public static function onOutputPageMakeCategoryLinks( OutputPage &$out, $categories, &$links ) {
		self::$normalCats = array_keys( $categories, 'normal' );
	}

	/**
	 * @inheritDoc
	 */
	public function onDataLayerSetup(OutputPage $output, Skin $skin, array &$dataLayer) {
		$title = $output->getTitle();
		$extensionRegistry = ExtensionRegistry::getInstance();

		if ( $extensionRegistry->isLoaded( 'ArticleContentArea' ) ) {
			$dataLayer[ 'articleContentArea' ] = ArticleContentArea::getArticleContentArea( $title );
		}
		if ( $extensionRegistry->isLoaded( 'ArticleType' ) ) {
			$dataLayer[ 'articleType' ] = ArticleType::getArticleType( $title );
		}
	}
}
