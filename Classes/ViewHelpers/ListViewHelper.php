<?php

/***************************************************************
*  Copyright notice
*
*  (c) 2012 Torsten Schrade <schradt@uni-mainz.de>, Academy of sciences and literature, Mainz
*  
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 3 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class Tx_Paginator_ViewHelpers_Widget_ListViewHelper extends Tx_Fluid_Core_Widget_AbstractWidgetViewHelper {

	/**
	 * @var Tx_Paginator_ViewHelpers_Widget_Controller_ListController
	 */
	protected $controller;

	/**
	 * @param Tx_Paginator_ViewHelpers_Widget_Controller_ListController $controller
	 * @return void
	 */
	public function injectController(Tx_Paginator_ViewHelpers_Widget_Controller_ListController $controller) {
		$this->controller = $controller;
	}

	/**
	 *
	 * @param Tx_Extbase_Persistence_QueryResultInterface $objects
	 * @param string $as
	 * @param array $arguments
	 * @param array $configuration
	 * @return string
	 */
	public function render(Tx_Extbase_Persistence_QueryResultInterface $objects, $as, array $arguments = array(), array $configuration = array('itemsPerPage' => 10, 'maxPageNumberElements' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'showCount' => FALSE)) {
		return $this->initiateSubRequest();
	}
}
?>