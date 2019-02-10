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

use OutputPage;
use Title;

class Hooks {
	private static $normalCats = [];
	private static $ignoredPageGroupingNamespaces = [
		NS_CATEGORY, NS_FILE, NS_SPECIAL, NS_MEDIAWIKI
	];

	/**
	 * ResourceLoaderGetConfigVars hook handler
	 *
	 * @param array &$vars
	 */
	public static function onResourceLoaderGetConfigVars( &$vars ) {
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
	 * This hook is part of Extension:GoogleUniversalAnalytics.
	 *
	 * @param OutputPage &$out
	 * @param string &$jsCode JavaScript code for Google Universal Analytics snippet
	 */
	public static function onBeforeGoogleAnalyticsSendPageView( OutputPage &$out, &$jsCode ) {
		$title = $out->getTitle();
		$ns = $title->getNamespace();
		if ( isset( $ns ) && in_array( $ns, self::$ignoredPageGroupingNamespaces ) ) {
			$jsCode .= PHP_EOL . "/* Namespace excluded from page grouping */" . PHP_EOL;
		} else {
			$normalCats = self::$normalCats;
			if ( count( $normalCats ) > 1 ) {
				$normalCats[0] = Title::makeTitleSafe( NS_CATEGORY, $normalCats[0] )->getText();
				$normalCats[1] = Title::makeTitleSafe( NS_CATEGORY, $normalCats[1] )->getText();
				$jsCode .= "ga('set', 'contentGroup2', '{$normalCats[1]}');" . PHP_EOL
						   . "ga('set', 'contentGroup3', '{$normalCats[0]}');" . PHP_EOL;
			};
		};
	}
}
