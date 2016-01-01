<?php
namespace ADWLM\Paginator\ViewHelpers\Widget;
/***************************************************************
*  Copyright notice
*
*  (c) 2015 Torsten Schrade <Torsten.Schrade@adwmainz.de>, Academy of Sciences and Literature | Mainz
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

use \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetViewHelper;
use \TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * This ViewHelper paginates a list of objects (from an extbase query result).
 * The current view's arguments should contain an integer "currentPage" argument.
 *
 * = Example =
 *
 * {namespace paginate = ADWLM\Paginator\ViewHelpers}
 *
 * <paginate:widget.list objects="{objects}" as="paginatedObjects" arguments="{arguments}" configuration="{itemsPerPage: 50, maxPageNumberElements: 10, insertAbove: 1, insertBelow: 1, showCount: 1}">
 *
 * <f:for each="{paginatedObjects}" as="{object}" iteration="iterator" />
 * <f:alias map="{currentItem : '{paginate:ItemCount(currentPage : arguments.currentPage, currentItem : iterator.cycle, itemsPerPage : 50)}'}">
 *
 * <f:link.action action="show" arguments="{object : object, currentItem : currentItem, currentPage : arguments.currentPage}">
 *
 * {currentItem}) {object.name}
 *
 * </f:link.action>
 *
 * </f:alias>
 * </f:for>
 *
 * </paginate:widget.list>
 */

class ListViewHelper extends AbstractWidgetViewHelper {

	/**
	 * Injects the controller
	 *
	 * @var \ADWLM\Paginator\ViewHelpers\Widget\Controller\ListController
	 * @inject
	 */
	protected $controller;

	/**
	 * Initiates a sub request to the widget controller that displays a list view pagination
	 *
	 * @param QueryResultInterface $objects The objects to paginate
	 * @param string $as Name of the template variable that contains the paginated objects
	 * @param array $arguments The current request's arguments
	 * @param array $configuration The configuration for this widget
	 *
	 * @return string
	 */
	public function render(QueryResultInterface $objects, $as, array $arguments = array(), array $configuration = array('itemsPerPage' => 10, 'maxPageNumberElements' => 10, 'insertAbove' => FALSE, 'insertBelow' => TRUE, 'showCount' => FALSE)) {
		return $this->initiateSubRequest();
	}
}
?>