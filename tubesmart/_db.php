<?php

// TODO: Add a lot more exceptions.
// TODO: Clean it up to remove as much source as possible.

class Database
{
	private $link;
	public $table;

	public $num_rows = 0;
	public $affected_rows = 0;

	public function __construct($host, $user, $pass, $db)
	{
		if(!$this->link = mysql_connect($host, $user, $pass))
			die('error connecting');
		
		if(!mysql_select_db($db, $this->link))
			die('no database');
		
		$this->table = 'grabs';
	}

// ---------------------------------------------------------

	/**
	 * retrieve_all()
	 *
	 * Retrieves all of the records from the table.
	 * TODO: Possibly better way than bind_result to object array?
	 *
	 * @access	public
	 * @param	integer The amount of records to retrieve.
	 * @param	integer The position to start from when retrieving records.
	 * @return	array The array containing data objects.
	 */
	public function retrieve_all($limit=30, $offset=0)
	{
		$grabs = array();

		$sql = sprintf("SELECT * FROM `%s` ORDER BY `id` DESC LIMIT %d, %d", $this->table, $offset, $limit);
		$result = mysql_query($sql, $this->link);
		if(($this->num_rows = mysql_num_rows($result)) > 0)
		{
			while($grab = mysql_fetch_object($result))
				$grabs[] = $grab;

			return $grabs;
		}

		return FALSE;
	}

// ---------------------------------------------------------

	/**
	 * retrieve_where()
	 *
	 * Retrieves multiple records from the table.
	 * TODO: Possibly better way than bind_result to object array?
	 *
	 * @access	public
	 * @param	integer The amount of records to retrieve.
	 * @param	integer The position to start from when retrieving records.
	 * @return	array The array containing data objects.
	 */
	public function retrieve_where($field, $value, $limit=30, $offset=0)
	{
		$sql = sprintf("SELECT * FROM `%s` WHERE `%s` = '%s' ORDER BY `id` DESC LIMIT %d, %d;", $this->table, mysql_real_escape_string($field), mysql_real_escape_string($value), $offset, $limit);
		$result = mysql_query($sql, $this->link);

		if(($this->num_rows = mysql_num_rows($result)) > 0)
		{
			while($grab = mysql_fetch_object($result))
				$grabs[] = $grab;
			
			return $grabs;
		}

		return FALSE;

	}

	public function search($search, $limit=30, $offset=0)
	{
		$sql=sprintf("SELECT * FROM `%s` WHERE",$this->table);
		foreach($search as $key => $value)
			$sql .= sprintf(" `%s` = '%s' AND", mysql_real_escape_string($key), mysql_real_escape_string($value));
		
		$sql = substr($sql, 0, -4).sprintf(" ORDER BY `id` DESC LIMIT %d, %d", $offset, $limit);
		$result = mysql_query($sql, $this->link);
		/*if(($this->num_rows = mysql_num_rows($result)) > 0)
		{
			while($grab = mysql_fetch_object($result))
				$grabs[] = $grab;
			
			return $grabs;
		}*/
		
		return FALSE;

	}



// ---------------------------------------------------------

	/**
	 * retrieve_single()
	 *
	 * Retrieves a record from the table based on field name and it's value.
	 *
	 * @access 	public
	 * @param	string The name of the field to search through.
	 * @param	string The value of what you're searching for.
	 * @return	object The object of the record.
	 */
	public function retrieve_single($field, $value)
	{
		$sql = sprintf("SELECT * FROM `%s` WHERE `%s` = '%s' LIMIT 0, 1;", $this->table, mysql_real_escape_string($field), mysql_real_escape_string($value));
		if($result = mysql_query($sql, $this->link))
		{
			return mysql_fetch_object($result);
		}

		return FALSE;
	}

// ---------------------------------------------------------

	/**
	 * insert_single()
	 *
	 * Insert a single record into the table.
	 *
	 * @access	public
	 * @param	array	An array modeled after example usage.
	 * @return	bool
	 *
	 * -----------------------------------------------------
	 *
	 * USAGE:
	 * $record = array('user_id' => 1, 'filename' => 'filename', 'description' => 'This is a demo grab.');
	 * if($grabs->insert_single($record))
	 *	echo 'The record was inserted properly';
	 *
	 */
	public function insert_single($record)
	{
		$keys = array_keys($record);
		$values = array_values($record);

		$sql = sprintf("INSERT INTO `%s` (", $this->table);
		foreach($keys as $key)
			$sql .= sprintf(" `%s`,", mysql_real_escape_string($key));
		$sql = substr($sql, 0, -1).') VALUES (';
		foreach($values as $value)
			$sql .= sprintf(" '%s',", mysql_real_escape_string($value));
		$sql = substr($sql, 0, -1).');';

		if(!mysql_query($sql, $this->link))
			return FALSE;
		
		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * insert_many()
	 *
	 * Insert many records into the table.
	 * TODO: Make this more efficient.
	 *
	 * @access	public
	 * @param	array	An array modeled after example usage.
	 * @return	bool
	 *
	 * -----------------------------------------------------
	 *
	 * USAGE:
	 * $records = array();
	 * $records[] = array('user_id' => 1, 'filename' => 'filename', 'description' => 'This is a demo grab.');
	 * $records[] = array('user_id' => 1, 'filename' => 'filename', 'description' => 'This is a demo grab.');
	 * if($grabs->insert_single($records))
	 *	echo 'The record was inserted properly';
	 *
	 */
	public function insert_many($records)
	{
		foreach($records as $record)
		{
			if(!$this->insert_single($record))
				return FALSE;
		}

		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * update_single()
	 *
	 * Updates a record based for a single entry.
	 *
	 * @access	public
	 * @param	array	Array containing every field=>value that you're searching for.
	 * @param	array	The key/value relationship of each updated item.
	 * @return	bool
	 *
	 * NOTE: The second array ($update) can only have one key=>value set.
	 */
	public function update_single($record, $update)
	{
		// Update `table` SET..
		$sql = sprintf("UPDATE `%s` SET", $this->table);

		// Loop through each key=>value and build the query.
		foreach($record as $key => $value)
			$sql .= sprintf(" `%s` = '%s',", mysql_real_escape_string($key), mysql_real_escape_string($value));

		// Remove the comma and add the where value based on a single key=>value.
		$sql = substr($sql, 0, -1).sprintf("WHERE `%s` = '%s';", mysql_real_escape_string(key($update)), mysql_real_escape_string($update[key($update)]));

		// Try the query..
		if(!mysql_query($sql, $this->link))
			// Crash and burn.
			return FALSE;
		
		// Success!
		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * update_many()
	 *
	 * Update multiple records at one time.
	 *
	 * @access	public
	 * @param	array	Essentially multiple update_single() variables.
	 * @return	bool
	 *
	 * -----------------------------------------------------
	 *
	 * USAGE:
	 * $records = array();
	 * $records[] = array('field' => 'id', 'value' => 20, 'update' => array('description' => 'This is an example.'));
	 * $records[] = array('field' => 'id', 'value' => 23, 'update' => array('description' => 'This is an example.'));
	 * update_many($records); // Should return TRUE
	 *
	 */
	public function update_many($records)
	{
		// For every record (aka update_single())..
		foreach($records as $record)
		{
			// Update the value.
			if(!$this->update_single($record, $update))
				// The update failed.
				return FALSE;
		}

		// hell yeah, mate.
		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * remove_single()
	 *
	 * Removes a record based for a single entry.
	 *
	 * @access	public
	 * @param	array	Contains the field=>value for everything you're looking for.
	 * @return	bool
	 *
	 */
	public function remove_single($where)
	{
		// Delete all the records where..
		$sql = sprintf("DELETE FROM `%s` WHERE", $this->table);

		// Loop through and add all the key=>value to the where statement.
		foreach($where as $key => $value)
			$sql .= sprintf(" `%s` = '%s' AND", mysql_real_escape_string($key), mysql_real_escape_string($value));
		
		// Correct the extra comma and add a semicolon.
		$sql = substr($sql, 0, -4).';';

		// Run the query, check if it fails.
		if(!mysql_query($sql, $this->link)){
			// The query failed.
			header("HTTP/1.1 400 Bad Request");
			die(mysql_error());
			return FALSE;}
		
		// Smooth sailing.
		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * remove_many()
	 *
	 * Remove multiple records at one time.
	 *
	 * @access	public
	 * @param	array	Essentially multiple remove_single() variables.
	 * @return	bool
	 *
	 * -----------------------------------------------------
	 *
	 * USAGE:
	 * $records = array();
	 * $records[] = array('field' => 'id', 'value' => 20));
	 * $records[] = array('field' => 'id', 'value' => 23));
	 * remove_many($records); // Should return TRUE
	 *
	 */
	public function remove_many($wheres)
	{
		// For every command in a multiarray..
		foreach($wheres as $where)
		{
			// If there was an error that happened..
			if(!$this->remove_single($where))
				// Return false on the error.
				return FALSE;
		}

		// Everything went well.
		return TRUE;
	}

// ---------------------------------------------------------

	/**
	 * num_rows_where()
	 *
	 * Retrieves the total amount of rows based on a query.
	 *
	 * @access	public
	 * @param	string	The column to search in.
	 * @param	string	The value to search for.
	 * @return	integer	The amount of rows, returns FALSE on error.
	 *
	 */
	public function num_rows_where($field, $value, $limit=null, $offset=0)
	{
		// Count all of the rows where x=x.
		$sql = sprintf("SELECT COUNT(*) as `num_rows` FROM `%s` WHERE `%s` = '%s'", $this->table, mysql_real_escape_string($field), mysql_real_escape_string($value));

		// If limit was set, then add the limit and offset to it.
		if(!empty($limit))
			$sql .= sprintf(" LIMIT %d, %d;", $offset, $limit);

		// Query the database and set the result.
		$result = mysql_query($sql, $this->link);

		// Retrieve one row from the mysql resource.
		$row = mysql_fetch_object($result);

		// If the row value "num_rows" was set, and not empty..
		if(isset($row->num_rows) && !empty($row->num_rows))
			// Return an integer, typecast to be safe.
			return (int)$row->num_rows;

		// An error happened, returning false.
		return FALSE;

	}

}
