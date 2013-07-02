<?php
/*
 * Register necessary class names with autoloader
 *
 * $Id$
 */
$extensionPath = t3lib_extMgm::extPath('paginator');
return array(
	'tx_paginator_viewhelpers_widget_listviewhelper' => $extensionPath . 'Classes/ViewHelpers/ListViewHelper.php',
);
?>