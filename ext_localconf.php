<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

Tx_Extbase_Utility_Extension::configurePlugin(
	$_EXTKEY,
	'Frontend',
	array(
		'Search' => 'index'
	),
	// non-cacheable actions
	array(
		'Search' => 'index'
	)
);

?>