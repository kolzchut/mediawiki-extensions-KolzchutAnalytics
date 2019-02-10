# MediaWiki extension KolzchutAnalytics

This extension is used to separate Kol-Zchut-specific Google Analytics
customizations from the main GoogeUniversalAnalytics extensions.
It uses that extension's hooks to inject our own code.

Currently, it does the following:

| Feature              | Explanation
|----------------------|-------------------------------------------------------------------------------------------------------------------------
| Page Grouping        | Classify pages into Content Groups, based on the first 2 visible categories. You must set up content groups in advance.
| Visible Categories   | Add wgVisibleCategories to mw.config


# Dependencies
This extension has a hard dependency on GoogleUniversalAnalytics.
