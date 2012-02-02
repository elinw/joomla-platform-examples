<?php
/**
 * An example JWeb application built on the Joomla Platform.
 *
 * To run this example, copy or soft-link this folder to your web server tree.
 *
 * @package    Joomla.Examples
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

// We are a valid Joomla entry point.
define('_JEXEC', 1);

// Setup the base path related constant.
define('JPATH_BASE', dirname(__FILE__));
define('JPATH_SITE', JPATH_BASE);


// Increase error reporting to that any errors are displayed.
// Note, you would not use these settings in production.
error_reporting(E_ALL);
ini_set('display_errors', true);

// Bootstrap the application.
require dirname(dirname(dirname(__FILE__))).'/bootstrap.php';

// Import the JWeb class from the platform.
jimport('joomla.application.web');

/**
* An example JWeb application class.
*
* This example shows how to render an HTML table from a single database table.
* This assumes that you will create a database with the appropriate table name and columns.
* You can create that database using the sample_database.mysql file.
* You will need to change the configuration.php file to match your data
*
* @package  Joomla.Examples
* @since    11.3
*/
class DbTableRead extends JWeb
{
	public function __construct()
	{
		// Call the parent __construct method so it bootstraps the application class.
		parent::__construct();

		jimport('joomla.database.database');

		// Note, this will throw an exception if there is an error
		// creating the database connection.
		$this->dbo = JDatabase::getInstance(
			array(
				'driver' => $this->get('dbDriver'),
				'host' => $this->get('dbHost'),
				'user' => $this->get('dbUser'),
				'password' => $this->get('dbPass'),
				'database' => $this->get('dbName'),
				'prefix' => $this->get('dbPrefix'),
			)
		);
	}
	/**
	 * Overrides the parent doExecute method to run the web application.
	 *
	 * This method should include your custom code that runs the application.
	 *
	 * @return  void
	 *
	 * @since   11.3
	 */
	protected function doExecute()
	{
		// Get the query builder class from the database.
		$query = $this->dbo->getQuery(true);

		// Set up a query to select items in the '' table.
		$query->select('title, body, type_id, created_user_id')
			->from($this->dbo->qn('#__content'));

		// Push the query builder object into the database connector.
		$this->dbo->setQuery($query);
		$rows = $this->dbo->loadObjectList();

		// Create the table.
		echo '<table>';
		echo '<tr> <th>Title</th><th>Type</th><th>Created By</th></tr>';
		foreach ($rows as $i => $row)
		{
			echo '<tr>';
			echo '<td>' . $rows[$i]->title . '</td>';
			echo '<td>' . $rows[$i]->type_id . '</td>';
			echo '<td>' . $rows[$i]->created_user_id . '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}
}

// Instantiate the application object, passing the class name to JWeb::getInstance
// and use chaining to execute the application.
JWeb::getInstance('DbTableRead')->execute();
