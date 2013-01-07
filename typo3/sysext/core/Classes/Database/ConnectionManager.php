<?php
namespace TYPO3\CMS\Core\Database;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Daniel Hürtgen <huertgen@rheinschafe.de>
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
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * Handle all database connection driver used by typo3.
 *
 * @author Daniel Hürtgen <huertgen@rheinschafe.de>
 */
class ConnectionManager implements ConnectionManagerInterface, \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var array
	 */
	private $connections = array();

	/**
	 * @var string
	 */
	private $defaultConnectionName;

	/**
	 * Constructor.
	 *
	 * @param array $connections Array with dbal connection drivers
	 * @param string $defaultConnectionName String with default connection name
	 */
	public function __construct(array $connections, $defaultConnectionName = 'default') {
		$this->defaultConnectionName = $defaultConnectionName;
		$this->connections = $connections;
	}

	/**
	 * Gets the default connection name.
	 *
	 * @return string The default connection name
	 */
	public function getDefaultConnectionName() {
		return $this->defaultConnectionName;
	}

	/**
	 * Gets the named connection.
	 *
	 * @param string $name The connection name (null for the default one)
	 *
	 * @return object
	 */
	public function getConnection($name = NULL) {
		if ($name === NULL) {
			$name = $this->defaultConnectionName;
		}

		if (!isset($this->connections[$name])) {
			// throw exception
		}

		return $this->connections[$name];
	}

	/**
	 * Gets an array of all registered connections.
	 *
	 * @return array An array of Connection instances
	 */
	public function getConnections() {
		return $this->connections;
	}

	/**
	 * Gets all connection names.
	 *
	 * @return array An array of connection names
	 */
	public function getConnectionNames() {
		return array_keys($this->connections);
	}

}

?>