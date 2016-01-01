<?php
namespace ADWLM\Paginator\ViewHelpers\Widget\Controller;
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

use \TYPO3\CMS\Fluid\Core\Widget\AbstractWidgetController;
use \TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use \TYPO3\CMS\Core\Utility\ArrayUtility;
use \TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

class SingleController extends AbstractWidgetController {

	/**
	 * The submitted configuration
	 *
	 * @var array
	 */
	protected $configuration = array('itemsPerPage' => 10, 'showCount' => FALSE, 'objectArgumentName' => 'OBJECT');

	/**
	 * The objects from the query result
	 *
	 * @var QueryResultInterface
	 */
	protected $objects;

	/**
	 * Amount of items displayed per page
	 *
	 * @var integer
	 */
	protected $itemsPerPage = 1;

	/**
	 * The current page in the paginated set
	 *
	 * @var integer
	 */
	protected $currentPage = 1;

	/**
	 * The current item in the paginated set
	 *
	 * @var integer
	 */
	protected $currentItem = 1;

	/**
	 * The total items
	 *
	 * @var integer
	 */
	protected $totalItems = 1;

	/**
	 * The amount of pages in the paginated set
	 *
	 * @var integer
	 */
	protected $numberOfPages = 1;

	/**
	 * Configuration Manager
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 * @inject
	 */
	protected $configurationManager;

	/**
	 * Merges the submitted configuration and sets basic vars
	 *
	 * @return void
	 */
	public function initializeAction() {
		ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $this->widgetConfiguration['configuration'], TRUE);
		$this->objects = $this->widgetConfiguration['objects'];
		$this->totalItems = count($this->objects);
		((int) $this->widgetConfiguration['arguments']['currentPage'] > 0) ? $this->currentPage = (int) $this->widgetConfiguration['arguments']['currentPage'] : $this->currentPage = 1;
		((int) $this->widgetConfiguration['arguments']['currentItem'] > 0) ? $this->currentItem = (int) $this->widgetConfiguration['arguments']['currentItem'] : $this->currentItem = 1;
		$this->itemsPerPage = (int) $this->configuration['itemsPerPage'];
		$this->numberOfPages = ceil($this->totalItems / (int) $this->itemsPerPage);
	}

	/**
	 * Retrieves the original query from the extbase result set and executes four
	 * queries to find the first, previous, next and last items in the paginated set
	 * with a limit of 1 item and the current item as basis. Then builds and assigns
	 * the pagination to the single widget view.
	 *
	 * @return void
	 */
	public function indexAction() {

			// offset calculations for first, previous, next, last objects of the current result set
		$offsetFirst = 0;
		$offsetPrevious = (int) ($this->currentItem - 2);
		if ($offsetPrevious < 0) $offsetPrevious = 0;
		$offsetLast = (int) ($this->totalItems - 1);
		$offsetNext = (int) ($this->currentItem);
		if ($offsetNext > $offsetLast) $offsetNext = (int) $offsetLast;

			// item calculations
		$firstItem = 1;
		($this->currentItem == 1) ? $previousItem = 1 : $previousItem = $this->currentItem - 1;
		($this->currentItem == $this->totalItems) ? $nextItem = $this->totalItems : $nextItem = $this->currentItem + 1;
		$lastItem = $this->totalItems;

			// currentPage calculations
		$firstPage = 1;
		($previousItem <= (($this->currentPage - 1) * $this->itemsPerPage)) ? $previousPage = $this->currentPage - 1 : $previousPage = $this->currentPage;
		($nextItem > ($this->currentPage * $this->itemsPerPage)) ? $nextPage = $this->currentPage + 1 : $nextPage = $this->currentPage;
		$lastPage = $this->numberOfPages;

			// set query for first, previous, next, last uids
		$firstQuery = $this->objects->getQuery()->setLimit(1)->setOffset($offsetFirst);
		$previousQuery = $this->objects->getQuery()->setLimit(1)->setOffset($offsetPrevious);
		$lastQuery = $this->objects->getQuery()->setLimit(1)->setOffset((int) $offsetLast);
		$nextQuery = $this->objects->getQuery()->setLimit(1)->setOffset((int) $offsetNext);

			// build pagination
		$pagination = array(
			'firstPage' => $firstPage,
			'firstItem' => $firstItem,
			'firstUid' => $firstQuery->execute()->getFirst()->getUid(),
			'previousPage' => $previousPage,
			'previousItem' => $previousItem,
			'previousUid' => $previousQuery->execute()->getFirst()->getUid(),
			'nextPage' => $nextPage,
			'nextItem' => $nextItem,
			'nextUid' => $nextQuery->execute()->getFirst()->getUid(),
			'lastPage' => $lastPage,
			'lastItem' => $lastItem,
			'lastUid' => $lastQuery->execute()->getFirst()->getUid(),
			'totalItems' => $this->totalItems,
			'numberOfPages' => $this->numberOfPages,
			'currentPage' => $this->currentPage,
			'currentItem' => $this->currentItem
		);

		$this->view->assign('pagination', $pagination);

			// assign configuration and current arguments
		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('arguments', $this->widgetConfiguration['arguments']);

			// get configuration for assigning extension and plugin names for link view helper in widget template
		$frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$this->view->assign('frameworkConfiguration', array('extensionName' => $frameworkConfiguration['extensionName'], 'pluginName' => $frameworkConfiguration['pluginName']));
	}

}

?>