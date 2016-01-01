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
use \TYPO3\CMS\Core\Utility\ArrayUtility;

/**
 * Needed to dynamically set the argument key for the object to paginate in single view
 * since argument keys cannot be set dynamically with standard Fluid syntax
 *
 * = Example =
 *
 * {namespace paginate = ADWLM\Paginator\ViewHelpers}
 *
 * {paginate:SetObjectArgumentName(objectArgumentName : configuration.objectArgumentName, array : {OBJECT : pagination.lastUid, currentItem : pagination.lastItem, currentPage : pagination.lastPage})}
 *
 */

class SetObjectArgumentNameViewHelper extends AbstractViewHelper {

	/**
	 * Dynamically sets the object argument name as key
	 *
	 * @param string $objectArgumentName
	 * @param array $array
	 *
	 * @return array The modified array
	 */
	public function render($objectArgumentName, $array) {
		$keyValue = array($objectArgumentName => $array['OBJECT']);
		unset($array['OBJECT']);
		return $array + $keyValue;
	}
}
?>