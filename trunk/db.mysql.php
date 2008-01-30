<?php
/* --------------------------------------------------------------------------------------------
 * MYSQL - Database Class.
 * @version 1.0.1
 * @author James Becker <james@kungfucarrot.com>
 * @access public
 * 
 * Copyright: 2006,2007 James Becker.
 *
 * This class is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This class is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * You may contact the author of Config_File by e-mail at:
 * james@kungfucarrot.com
 *
 * The latest version of this class can be found:
 * http://www.kungfucarrot.com
---------------------------------------------------------------------------------------------*/

Class database_mysql {
	var $dbs = "localhost"; //Database Server, set here or on connection.
	var $dbd = "wolf"; //Database to use, set here or on connection or through changedb function.
	var $dbu = "root"; //Database username, set here or on connection.
	var $dbp = "telno1"; //Database password, set here or on connection.
	var $dbconn_id = 'database_mysql_connection'; //GLOBAL connection name.
	var $error = ""; //set on error - last error.
	var $errno = 0; //set on error - last error number (MySQL only).
	var $dbconn = ""; //used as temp connection.
	var $dbquery = ""; //used as temp query holder.
	var $altered = 0; //set when UPDATE,DELETE,REPLACE are used in a query.
	var $rowcount = 0; //set when SELECT is used in a query.
	var $insertID = 0; //set when INSERT is used, last ID will be set.
	//var $sql_statement = ""; //SQL statement from sql.statements.php

	/* ----------------------------------------------------------
	'' Function: database_mysql
	'' Usage: create object;
	'' Description: standard default constructor, will run on
	'' creation of new object.
	-----------------------------------------------------------*/
	function database_mysql() {
		//constructor
	}
	
	/* ----------------------------------------------------------
	'' Function: connect
	'' Usage: $object->connect([server],[username],[password]);
	'' Description: Use this to connect to MySQL server
	'' you can specify server,username,password to override the
	'' default ones specified above.
	-----------------------------------------------------------*/
	function connect($server = "", $username = "", $password = "", $initialDB = "") {
		if($server) $this->dbs = $server;
		if($username) $this->dbu = $username;
		if($password) $this->dbp = $password;
		if($initialDB) $this->dbd = $initialDB;

		if(!isset($GLOBALS[$this->dbconn_id])) {
			try {
				//create a connection
				$GLOBALS[$this->dbconn_id] = @mysql_connect($this->dbs,$this->dbu,$this->dbp);
			} catch(exception $e) {
				$this->error("Can't create database connection");
			}
			
			$this->selectdb();
		}
		//echo "Connection Made";
	}
	
	/* ----------------------------------------------------------
	'' Function: error
	'' Usage: $object->error(string [error]);
	'' Description: on MySQL error pass to this function to show
	'' MySQL error, for bespoke error pass string as error.
	-----------------------------------------------------------*/
	function error($strError = "") {
		if($strError) {
			print "<b>Error:</b> <i>" . $strError . "</i>";#
		} else {
			print "<b>Error " . @mysql_errno($this->dbconn) . "</b> <i>" . @mysql_error($this->dbconn) . "</i>";
		}
		exit;
	}

	/* ----------------------------------------------------------
	'' Function: selectdb
	'' Usage: $object->selectdb();
	'' Description: Call this function when you $this->dbd has
	'' been updated.
	-----------------------------------------------------------*/
	function selectdb() {
		try {
			@mysql_select_db($this->dbd,$GLOBALS[$this->dbconn_id]);
		} catch(exception $e) {
			$this->error();
		}
	}
	
	/* ----------------------------------------------------------
	'' Function: changedb
	'' Usage: $object->changedb(string database);
	'' Description:  Use to change database your using.
	-----------------------------------------------------------*/
	function changedb($usedb = "") {
		if($usedb == "") {
			$this->error("Function error, specify database to change to.");
		}
		
		$this->dbd = $usedb;
		$this->selectdb();
	}
	
	/* ----------------------------------------------------------
	'' Function: query
	'' Usage: $object->query(string query);
	'' Description: Use to pass query to MySQL server. All
	'' methods supported and will change the altered variable
	'' or number of rows.
	-----------------------------------------------------------*/
	function query($query = "") {
		$this->dbquery = @mysql_query($query,$GLOBALS[$this->dbconn_id]);
		
		$qType = strtolower(substr($query,0,strpos($query," ")));
	
		if(!$this->dbquery) {
			$this->error();
		} else {
			switch($qType) {
				case "select":
					$this->rowcount = mysql_num_rows($this->dbquery);
					break;
				case "insert":
					$this->insertID = @mysql_insert_id($GLOBALS[$this->dbconn_id]);
				case "update":
				case "delete":
					$this->altered = @mysql_affected_rows($GLOBALS[$this->dbconn_id]);
					break;
			}
			//echo "Query Made";
			return true;
		}
	}
	
	/* ----------------------------------------------------------
	'' Function: num_rows()
	'' Usage: $object->num_rows(void);
	'' Description: Returns the number of rows returned.
	-----------------------------------------------------------*/
	function num_rows() {
		return($this->rowcount);
	}
	
	/* ----------------------------------------------------------
	'' Function: insert_id
	'' Usage: $object->insert_id(void);
	'' Description: Retrieve the DB insert ID for the last query
	-----------------------------------------------------------*/
	function insert_id() {
		return $this->insertID;
	}
	
	/* ----------------------------------------------------------
	'' Function: fetch_row
	'' Usage: $object->fetch_row(void);
	'' Description: Use this to retreive next row in recordset
	'' will return false on EOF.
	-----------------------------------------------------------*/
	function fetch_row() {
		$row = @mysql_fetch_row($this->dbquery);
		if($row) {
			return $row;
		} else {
			return false;
		}
	}

	/* ----------------------------------------------------------
	'' Function: getAssocRow
	'' Usage: $object->getAssocRow;
	'' Description: Use this to retreive next row in recordset
	'' record set will be in the form of an associative array,
	'' will return false on EOF.
	-----------------------------------------------------------*/
	function fetch_array() {
		$row = @mysql_fetch_array($this->dbquery);
		if($row) {
			return $row;
		} else {
			return false;
		}
	}
}
?>