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

class ListController extends AbstractWidgetController {

	/**
	 * The widget configuration
	 *
	 * @var array
	 */
	protected $configuration = array('itemsPerPage' => 10, 'maxPageNumberElements' => 10, 'insertAbove' => TRUE, 'insertBelow' => TRUE, 'showCount' => FALSE);

	/**
	 * Objects to paginate
	 *
	 * @var QueryResultInterface
	 */
	protected $objects;

	/**
	 * The current page within the paginated set
	 *
	 * @var integer
	 */
	protected $currentPage = 1;

	/**
	 * The number of pages in the paginated set
	 *
	 * @var integer
	 */
	protected $numberOfPages = 1;

	/**
	 * Amount of items to show per page
	 *
	 * @var integer
	 */
	protected $itemsPerPage = 1;

	/**
	 * The maximum amount of pages to show at a time
	 *
	 * @var integer
	 */
	protected $maxPageNumberElements = 1;

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
		ArrayUtility::mergeRecursiveWithOverrule($this->configuration, $this->widgetConfiguration['configuration']);
		$this->objects = $this->widgetConfiguration['objects'];
		$this->itemsPerPage = (int) $this->configuration['itemsPerPage'];
		$this->maxPageNumberElements = (int) $this->configuration['maxPageNumberElements'];
		((int) $this->widgetConfiguration['arguments']['currentPage'] > 0) ? $this->currentPage = (int) $this->widgetConfiguration['arguments']['currentPage'] : $this->currentPage = 1;
	}

	/**
	 * Retrieves the original query from the extbase query result and executes it
	 * with a limit. Also provides experimental support for extbase queries in form
	 * of MySQL statements. Builds and assigns the pagination to the widget list view.
	 *
	 * @return void
	 */
	public function indexAction() {

			// get current query from QueryResultInterface
		$query = $this->objects->getQuery();
		if (is_object($query->getStatement())) {
			$statement = $query->getStatement()->getStatement();
		}

			// number of pages
		if ($statement) {
			$countQuery = preg_replace('/SELECT(.*?)FROM/', 'SELECT COUNT(*) FROM', $statement);
			$res = $GLOBALS['TYPO3_DB']->sql_query($countQuery);
			$row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res);
			if ($row['COUNT(*)'] > 0) {
				$this->numberOfPages = ceil($row['COUNT(*)'] / $this->itemsPerPage);
			}
		} else {
				// perform count
			$this->numberOfPages = ceil(count($this->objects) / $this->itemsPerPage);
		}

			// keeps current page equal to total number of pages
		if ($this->currentPage > $this->numberOfPages) $this->currentPage = $this->numberOfPages;

			// query limit
		if ($statement) {
			$offset = (int) ($this->itemsPerPage * ($this->currentPage - 1));
			if ($offset < 1) $offset = 0; 
			$query->statement($statement . ' LIMIT ' . $offset . ',' . $this->itemsPerPage);
		} else {
			$query->setLimit($this->itemsPerPage);
			if ($this->currentPage > 1) {
				$query->setOffset((int) ($this->itemsPerPage * ($this->currentPage - 1)));
			}
		}

			// assign template variables
		$this->view->assign('objects', array($this->widgetConfiguration['as'] => $query->execute()));
		$this->view->assign('configuration', $this->configuration);
		$this->view->assign('pagination', $this->buildPagination());
		$this->view->assign('figures', $this->getFigures());
		$this->view->assign('arguments', $this->widgetConfiguration['arguments']);

			// get configuration for assigning extension and plugin names for link view helper in widget template
		$frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
		$this->view->assign('frameworkConfiguration', array('extensionName' => $frameworkConfiguration['extensionName'], 'pluginName' => $frameworkConfiguration['pluginName']));
	}

	/**
	 * Calculates the values for the pagination.
	 * Returns an array with the keys "pages", "currentPage", "numberOfPages", "nextPage" and "previousPage"
	 *
	 * @return array
	 */
	protected function buildPagination() {

		$pages = array();

		for ($i = 1; $i <= $this->numberOfPages; $i++) {
			$pages[] = array('number' => $i, 'isCurrent' => ($i === $this->currentPage));
		}

		if ($this->maxPageNumberElements > 0) {

			$edgePages = array();

				// find the numbers 'at the edges' of a range
			foreach ($pages as $key => $value) {
					// generally write all keys from which a new range starts into this array
				if (($key % $this->maxPageNumberElements) == 0) $edgePages[] = $key;
					// determine where the current page fits in - this is the basis for the later range detection
				if ($key == $this->currentPage) $edgePages[] = $key;
			}

				// necessary if we're on the very last page - this will not yet be in edgePages since the foreach loop stops one short in regard to the values
			if ($this->currentPage > (count($pages)-1)) $edgePages[] = $this->currentPage;

			$currentPageLocation = array_search($this->currentPage, $edgePages);
			$pageRange = array_slice($pages, $edgePages[$currentPageLocation-1], $this->maxPageNumberElements);

			$pages = $pageRange;
		}

		$pagination = array(
			'pages' => $pages,
			'currentPage' => $this->currentPage,
			'numberOfPages' => $this->numberOfPages,
			'previousPageRange' => $edgePages[$currentPageLocation-1],
			'nextPageRange' => ($edgePages[$currentPageLocation+1] != NULL) ? $edgePages[$currentPageLocation+1]+1 : 0
		);

		if ($this->currentPage < $this->numberOfPages) {
			$pagination['nextPage'] = $this->currentPage + 1;
		}

		if ($this->currentPage > 1) {
			$pagination['previousPage'] = $this->currentPage - 1;
		}

		return $pagination;
	}

	/**
	 * Gets the figures for the result count ("showing results X from Y")
	 *
	 * @return array
	 */
	protected function getFigures() {
		$figures = array();

		$figures['total'] = count($this->objects);

		$figures['rangeFrom'] = $this->itemsPerPage * ($this->currentPage - 1);
		if ($figures['rangeFrom'] < 1) $figures['rangeFrom'] = 1;

		$figures['rangeTo'] = $this->itemsPerPage * ($this->currentPage);
		if ($figures['rangeTo'] > $figures['total']) $figures['rangeTo'] = $figures['total'];

		return $figures;
	}
}

?>