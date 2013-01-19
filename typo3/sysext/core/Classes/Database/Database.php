<?php
namespace TYPO3\CMS\Core\Database;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Alexander Schnitzler <alex.schnitzler@typovision.de>
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
/**
 * A database handling helper class
 *
 * @author Alexander Schnitzler <alex.schnitzler@typovision.de>
 */
class Database {

	/**
	 * @var boolean
	 */
	static protected $isDatabaseFrameworkInitialized = FALSE;

	/**
	 * Initializes the database framework by loading the cache manager and factory
	 * into the global context.
	 *
	 * @return void
	 */
	static public function initializeDatabaseFramework() {
		if (!self::$isDatabaseFrameworkInitialized) {
			// New operator used on purpose, makeInstance() is not ready to be used so early in bootstrap
			$GLOBALS['typo3DatabaseConnectionManager'] = new \TYPO3\CMS\Core\Database\ConnectionManager();
			\TYPO3\CMS\Core\Utility\GeneralUtility::setSingletonInstance('TYPO3\\CMS\\Core\\Database\\ConnectionManager', $GLOBALS['typo3DatabaseConnectionManager']);
			$GLOBALS['typo3DatabaseConnectionManager']->setDatabaseConfigurations($GLOBALS['TYPO3_CONF_VARS']['DB']);
			// New operator used on purpose, makeInstance() is not ready to be used so early in bootstrap
			$GLOBALS['typo3DatabaseConnectionFactory'] = new \TYPO3\CMS\Core\Database\ConnectionFactory('production', $GLOBALS['typo3DatabaseConnectionManager']);
			\TYPO3\CMS\Core\Utility\GeneralUtility::setSingletonInstance('TYPO3\\CMS\\Core\\Database\\ConnectionFactory', $GLOBALS['typo3DatabaseConnectionFactory']);
			self::$isDatabaseFrameworkInitialized = TRUE;
		}
	}

	/**
	 * Determines whether the database framework is initialized.
	 * The database framework could be disabled for the core but used by an extension.
	 *
	 * @return boolean True if database framework is initialized
	 */
	static public function isDatabaseFrameworkInitialized() {
		if (!self::$isDatabaseFrameworkInitialized && isset($GLOBALS['typo3DatabaseConnectionManager']) && $GLOBALS['typo3DatabaseConnectionManager'] instanceof \TYPO3\CMS\Core\Database\ConnectionManager && isset($GLOBALS['typo3DatabaseConnectionFactory']) && $GLOBALS['typo3DatabaseConnectionFactory'] instanceof \TYPO3\CMS\Core\Database\ConnectionFactory) {
			self::$isDatabaseFrameworkInitialized = TRUE;
		}
		return self::$isDatabaseFrameworkInitialized;
	}
}

?>