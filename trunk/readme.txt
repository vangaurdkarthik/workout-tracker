The Database Library
============================
When people mention PHP and databases you initially think MySQL right? Well don't forget PHP can also connect to so much more! Including the all powerful Microsoft SQL Server.
I've written two lightweight classes that hopefully will help in your quest for database integration, currently we have two flavours

Get the latest version of these class files at: http://www.kungfucarrot.com

Microsoft SQL Server
============================

Note: Windows Only
This version of the library is for Windows ONLY! It uses the COM object of the Windows operating system to create an ASP'esk connection/recordset combination.

MySQL Server
============================

This version of the library is for any server running PHP and allows the connection to a MySQL database.

Getting Started
============================

Example initiation code block:

----------------------------
Define("DBSERVER",[string server address]);
Define("DBUSER",[string username]);
Define("DBPASS",[string password]);
Define("DATABASE",[string database name]);

$db = new database_[classname]();
$db->connect(DBSERVER,DBUSER,DBPASS,DATABASE);
----------------------------

Note: Where [classname] is replace with either database_dsn_mssql or database_mysql ;)

The Methods
============================

Connect
----------------------------
$object->connect($server,$username,$password,$initial_database);
Initiates a connection to the database

Query
----------------------------
$object->query($sql_query);
Execute an SQL query on an open connection

Number of Rows
----------------------------
$object->num_rows();
Will return the number of rows from the last query

Insert ID
----------------------------
$object->insert_id();
Will return the auto_number/key number for the last inserted record

Data Seek
----------------------------
$object->data_seek($record_position);
You can move around your created recordset by supplying a integer of the position of the record within the recordset

Fetch Row
----------------------------
$data = $object->fetch_row();
Will return an enumerated array (0,1,2... etc.) of the current record

Example:
----------------------------
$data = $db->fetch_row();
echo $data[0]; 
----------------------------

or 

----------------------------
while($data = $db->fetch_row()) {
     $val0 = $data[0];
} 
----------------------------

Fetch Array
----------------------------
$data = $object->fetch_array();
Will return an associative array ('fred'=>1,'bob'=>2,.. etc.) of the current record

Example:
----------------------------
$data = $db->fetch_array();
echo "My Name is: " . $data['name']; 
----------------------------

or 

----------------------------
while($data = $db->fetch_array()) {
     $myName = $data['name'];
} 
----------------------------

Change Database
----------------------------
$object->changedb($database_name);
Change your current database to a new database, will use the same username and password combination already assigned, this method is really for jumping around databases on the same server.

kill Connection
----------------------------
$object->killConnection();
Terminates an open connection, sometimes useful on a limited Microsoft SQL server that doesn't allow lots of connections.
