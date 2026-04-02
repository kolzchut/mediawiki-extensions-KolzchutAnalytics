# MediaWiki extension KolzchutAnalytics

This extension is used to separate Kol-Zchut-specific Google Analytics
customizations from the main GoogeUniversalAnalytics extensions.
It uses that extension's hooks to inject our own code.

Currently, it does the following:

| Feature               | Explanation                                             |
|-----------------------|---------------------------------------------------------|
| Metadata in DataLayer | Add articleType & articleContentArea into the dataLayer |
| Visible Categories    | Add wgVisibleCategories to mw.config                    |

## Dependencies
This extension has a hard dependency on [extension:GoogleTagManager](https://github.com/kolzchut/mediawiki-extensions-GoogleTagManager/).

## Changelog

### 1.1.1
- Modernize to use AutoloadNamespaces, instance-based hook handlers,
  and namespaced class imports
- Implement hook interfaces (MakeGlobalVariablesScriptHook)
- Replace deprecated OutputPageMakeCategoryLinks hook with
  OutputPage::getCategories('normal')
- Update dev dependencies to current versions

### 1.1.0
- Remove the dependency and usage of hook GoogleAnalytics::SendPageView
- Hook DataLayerSetup from extension:GoogleTagManager and use it to push
  articleType & articleContentArea into the dataLayer

### 1.0.1
(bugfix) use hook `MakeGlobalVariablesScript` instead of `ResourceLoaderGetConfigVars`

### 1.0.0
Initial release - code split off from GoogleUniversalAnalytics.
