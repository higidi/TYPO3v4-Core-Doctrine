<?php
namespace TYPO3\CMS\Core\Database;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012-2013 Daniel Hürtgen <huertgen@rheinschafe.de>
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
 * @author Alexander Schnitzler <alex.schnitzler@typovision.de>
 * @scope singleton
 */
class ConnectionManager implements ConnectionManagerInterface, \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * @var array
	 */
	private $connections = array();

	/**
	 * @var array
	 */
	private $databaseConfigurations = array();

	/**
	 * @var string
	 */
	private $defaultConnectionName;

	/**
	 * @var \TYPO3\CMS\Core\Database\ConnectionFactory
	 */
	protected $connectionFactory;

	/**
	 * @param \TYPO3\CMS\Core\Database\ConnectionFactory $connectionFactory
	 */
	public function injectConnectionFactory(\TYPO3\CMS\Core\Database\ConnectionFactory $connectionFactory) {
		$this->connectionFactory = $connectionFactory;
	}

	/**
	 * @param array $databaseConfigurations
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function setDatabaseConfigurations(array $databaseConfigurations) {
		unset($GLOBALS['TYPO3_CONF_VARS']['DB']);

		// Fallback
		if(empty($databaseConfigurations['connections'])) {
			$databaseConfigurations['connections']['default'] = array(
				'write' => array(
					'dbname' => $databaseConfigurations['database'],
					'driver' => 'pdo_mysql',
					'host' => $databaseConfigurations['host'],
					'password' => $databaseConfigurations['password'],
					'user' => $databaseConfigurations['username'],
					'charset' => 'utf-8',
					'unix_socket',
					'driverOptions' => array(),
				)
			);
			$databaseConfigurations['connections']['default']['read'] = array($databaseConfigurations['connections']['default']['write']);
			unset(
				$databaseConfigurations['database'],
				$databaseConfigurations['host'],
				$databaseConfigurations['password'],
				$databaseConfigurations['username']
			);
		}

		foreach ($databaseConfigurations['connections'] as $identifier => $configuration) {
			if (!is_array($configuration)) {
				throw new \InvalidArgumentException('The database configuration for database "' . $identifier . '" was not an array as expected.', 1358510870);
			}
			$this->databaseConfigurations[$identifier] = $configuration;
		}
	}

	/**
	 * Instantiates all registered connections.
	 *
	 * @return void
	 */
	protected function createAllConnections() {
		foreach (array_keys($this->databaseConfigurations) as $identifier) {
			if (!isset($this->connections[$identifier])) {
				$this->createConnection($identifier);
			}
		}
	}

	/**
	 * Instantiates the connection for $identifier.
	 *
	 * @param string $identifier
	 * @return void
	 */
	protected function createConnection($identifier) {
		$this->connectionFactory->create($identifier);
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