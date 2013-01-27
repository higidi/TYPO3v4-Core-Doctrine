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
 * @api
 */
class ConnectionManager implements ConnectionManagerInterface, \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Holds all connections.
	 *
	 * @var array
	 */
	private $connections = array();

	/**
	 * Holds all connection pools.
	 *
	 * @var array
	 */
	private $connectionPools = array();

	/**
	 * Holds an array with all connection configurations.
	 *
	 * @var array
	 */
	private $connectionConfigurations = array();

	/**
	 * Holds an array with all connection-pool configurations.
	 *
	 * @var array
	 */
	private $connectionPoolConfigurations = array();

	/**
	 * Connection identifer used as default connection (fallback).
	 *
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
	 * Set array with connection configurations.
	 *
	 * @param array $connectionConfigurations Array of $identifier to $params configurations.
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function setConnectionConfigurations(array $connectionConfigurations) {
		foreach ($connectionConfigurations as $identifier => $params) {
			if (!is_array($params)) {
				throw new \InvalidArgumentException('The database connection configuration for database "' . $identifier . '" was not an array as expected.', 1358510870);
			}
			$this->setConnectionConfiguration($identifier, $params);
		}
	}

	/**
	 * Sets connection params for identifier.
	 *
	 * @param string $identifier Unique connection identifier
	 * @param array  $params     Array with connection params
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function setConnectionConfiguration($identifier, array $params) {
		$this->connectionConfigurations[$identifier] = $params;
	}

	/**
	 * Gets all connection configurations.
	 *
	 * @return array An array with all connection configurations.
	 */
	public function getConnectionConfigurations() {
		return $this->connectionConfigurations;
	}

	/**
	 * Get connection configuraiton by identifier.
	 *
	 * @param string $identifier Unique connection identifier
	 * @return null|array NULL if configuraiton not exists, otherwise an array with connection params.
	 */
	public function getConnectionConfiguration($identifier) {
		return $this->hasConnectionConfiguration($identifier) ? $this->connectionConfigurations[$identifier] : NULL;
	}

	/**
	 * Checks wheater a connection configuration by given identifier exists.
	 *
	 * @param string $identifier Unique connection identifier
	 * @return boolean
	 */
	public function hasConnectionConfiguration($identifier) {
		return array_key_exists($identifier, $this->connectionConfigurations) ? TRUE : FALSE;
	}

	/**
	 * Set array with connection pool configurations.
	 *
	 * @param array $connectionPoolConfigurations Array of $name to $poolConfigurations.
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function setConnectionPoolConfigurations(array $connectionPoolConfigurations) {
		foreach ($connectionPoolConfigurations as $name => $poolConfiguration) {
			if (!is_array($poolConfiguration)) {
				throw new \InvalidArgumentException('The database connection pool configuration for pool "' . $name . '" was not an array as expected.', 1359247408);
			}
			$this->setConnectionPoolConfiguration($name, $poolConfiguration);
		}
	}

	/**
	 * Sets connection pool configuration for pool name.
	 *
	 * @param string $name              Unique connection pool name
	 * @param array  $configuration     Array with connection pool configuration
	 * @throws \InvalidArgumentException
	 * @return void
	 */
	public function setConnectionPoolConfiguration($name, array $configuration) {
		$this->connectionPoolConfigurations[$name] = $configuration;
	}

	/**
	 * Gets all connection pool configurations.
	 *
	 * @return array An array with all connection pool configurations.
	 */
	public function getConnectionPoolConfigurations() {
		return $this->connectionPoolConfigurations;
	}

	/**
	 * Get connection pool configuraiton by name.
	 *
	 * @param string $name Unique connection pool name
	 * @return null|array NULL if configuraiton not exists, otherwise an array with connection params.
	 */
	public function getConnectionPoolConfiguration($name) {
		return $this->hasConnectionPoolConfiguration($name) ? $this->connectionPoolConfigurations[$name] : NULL;
	}

	/**
	 * Checks wheater a connection pool configuration by given identifier exists.
	 *
	 * @param string $name Unique connection pool name
	 * @return boolean
	 */
	public function hasConnectionPoolConfiguration($name) {
		return array_key_exists($name, $this->connectionConfigurations) ? TRUE : FALSE;
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