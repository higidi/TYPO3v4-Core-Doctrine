<?php
namespace TYPO3\CMS\Core\Database;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Alexander Schnitzler <alex.schnitzler@typovision.de>
 *  (c) 2013 Daniel Hürtgen <huertgen@rheinschafe.de>
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
 * This database factory takes care of instantiating a database connection.
 * After creation of the new connection, the connection will be registered
 * at the connection manager.
 *
 * This file is a backport from FLOW3
 *
 * @author Alexander Schnitzler <alex.schnitzler@typovision.de>
 * @author Daniel Hürtgen <huertgen@rheinschafe.de>
 * @scope singleton
 * @api
 */
class ConnectionFactory implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Constant contains default connection wrapper class name.
	 */
	const DEFAULT_CONNECTION_WRAPPER_CLASS = 'TYPO3\\CMS\\Core\\Database\\Connection';

	/**
	 * The current FLOW3 context ("production", "development" etc.)
	 *
	 * TYPO3 v4 note: This variable is always set to "production"
	 * in TYPO3 v4 and only kept in v4 to keep v4 and FLOW3 in sync.
	 *
	 * @var string
	 */
	protected $context;

	/**
	 * A reference to the cache manager
	 *
	 * @var \TYPO3\CMS\Core\Database\ConnectionManager
	 */
	protected $connectionManager;

	/**
	 * Constructs this database factory.
	 *
	 * @param string                                     $context           The current FLOW3 context
	 * @param \TYPO3\CMS\Core\Database\ConnectionManager $connectionManager The connection manager
	 */
	public function __construct($context, \TYPO3\CMS\Core\Database\ConnectionManager $connectionManager) {
		$this->context = $context;
		$this->connectionManager = $connectionManager;
		$this->connectionManager->injectConnectionFactory($this);
	}

	/**
	 * Creates new database connection.
	 *
	 * @param string $identifier
	 * @throws \InvalidArgumentException
	 * @return \TYPO3\CMS\Core\Database\Connection
	 */
	public function create($identifier) {
		// Not yet implemented
	}
}

?>