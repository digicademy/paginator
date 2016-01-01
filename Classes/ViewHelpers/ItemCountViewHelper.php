<?php
namespace ADWLM\Paginator\ViewHelpers;
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
 *  the Free Software Foundation; either version 2 of the License, or
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

use \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Calculates the current item count based on the current page, the maximum items per page and
 * the current result iterator item.
 *
 * = Example =
 *
 * {namespace paginate = ADWLM\Paginator\ViewHelpers}
 *
 * {paginate:ItemCount(currentPage : arguments.currentPage, currentItem : iterator.cycle, itemsPerPage : 50)}
 *
 */

class ItemCountViewHelper extends AbstractViewHelper {

	/**
	 * Calculates and renders the item count
	 *
	 * @param integer $currentPage
	 * @param integer $currentItem
	 * @param integer $itemsPerPage
	 *
	 * @return integer Count for the current item
	 */
	public function render($currentPage, $currentItem, $itemsPerPage) {
			// makes sure an integer value is set when the argument is submitted without a value (for instance if it doesn't exist)
		if (is_null($currentPage)) $currentPage = 1;
		if (is_null($currentItem)) $currentItem = 1;
		if (is_null($itemsPerPage)) $itemsPerPage = 1;
		return (int) ((($currentPage - 1) * $itemsPerPage) + $currentItem);
	}
}
?>