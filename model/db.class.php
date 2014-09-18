<?php
	class db
	{
		/**
 		* The actual connection resource.
 		* @var connection
 		*/
		var $connection;
		/**
 		* Holds the array of results from the query.
 		* @var array 
 		*/
		var $data;
		/**
 		* The last queried string.
 		* @var string
 		*/
		var $last_query;
		/**
 		* The CURRENT query.
 		* @var string
 		*/
		var $query;
		/**
 		* The Result, equal to mysql_result($this-connection);
 		* @var resource
 		*/
		Var $result;
		/**
 		* The Result, equal to mysql_result($this-connection);
 		* @var resource
 		*/
		Var $result_type;
		/**
 		* Either the amount of the last queried results, or the affected rows.
 		* @var string int
 		*/
		var $count;
		/**
 		* The total number of querys that this object has made since instantiation.
 		* @var string int
 		*/
		var $query_count = 0;
                /**
 		* Whether the connection is successful
 		* @var boolean
 		*/
		var $connected;
		
		function __construct($db_connect = array())
		{
			//connect to the database
			
                        if(isset($_SESSION)){$d = $_SESSION['config']['db_conn'];$name = $d['name'];
			
                            if (isset($d['host']) && isset($d['user']) && isset($d['password']) && isset($d['db']))
                            {
                    		$this->connection = mysql_connect($d['host'], $d['user'], $d['password']);
            			mysql_select_db($d['db']);
                                if (mysql_error($this->connection))
                                        {
                                            $this->connected = FALSE;
                                            trigger_error("DATABASE CONNECTION: UNSUCCESSUL<br/>Error: ".mysql_error($this->connection), E_USER_WARNING);
                                        }
                                        else
                                        {
                                            $this->connected = TRUE;
                                            //trigger_error("DATABASE CONNECTION: SUCCESSUL<br/>Connected to: ".$db_connect['db']." on ".$db_connect['host'], E_USER_NOTICE);
                                        }
                            }
                        }
			elseif(isset($db_connect))
			{
				if (isset($db_connect['host']) && isset($db_connect['user']) && isset($db_connect['password']) && isset($db_connect['db']))
				{
					$this->connection = mysql_connect($db_connect['host'], $db_connect['user'], $db_connect['password']);
					mysql_select_db($db_connect['db']);
                                        if (mysql_error($this->connection))
                                        {
                                            $this->connected = FALSE;
                                            trigger_error("DATABASE CONNECTION: UNSUCCESSUL<br/>Error: ".mysql_error($this->connection), E_USER_WARNING);
                                        }
                                        else
                                        {
                                            $this->connected = TRUE;
                                            //trigger_error("DATABASE CONNECTION: SUCCESSUL<br/>Connected to: ".$db_connect['db']." on ".$db_connect['host'], E_USER_NOTICE);
                                        }
                                }
			}
			
			else
			{
                            $this->connected = FALSE;
				trigger_error("Connection Error - Database connection string is incorrect.", E_USER_WARNING);
			}
		}
		/**
 		* TODO: disconnect from database.
 		*/
		function __destruct()
		{
                        //trigger_error("Disconnect after ".$this->query_count." queries.", E_USER_NOTICE);
			//mysql_close($this->connection);
		}
		/**
 		* This is the method that queries the database.
 		* @param bool $debug  Weather or not to print debug info.
 		* @param bool $record If true, will save the query to a database along with user info.
 		*/
		function go($debug=FALSE, $result_type = MYSQL_ASSOC)
		{
			$this->result_type = $result_type;
			$this->query_count++;
			if($debug == FALSE)
			{
				if ($this->query_type == "Non-Select")
				{
					$this->set_non_select($debug);
					return $this->data;
				}
			else
				{
					$this->set_select($debug);
					return FALSE;
					
				}
			return $this->data;
			}
			else
			{
				if ($this->query_type == "Non-Select")
				{
					$this->set_non_select($debug);
					return $this->data;
					//$this->throw_error();
				}
				else
				{
					$this->set_select($debug);
					return FALSE;
				}
			}        
		}
		function throw_error()
		{
			echo "<br>There was an error with the query: ".$this->query." <br>Error is: ".mysql_error($this->connection); 
		}
		//All query building methods below here
		/**
 		* @category QueryBuilder
 		* @param bool $column The column of the table to query against.
 		*/
		function select($column="*")
		{
			$this->query_type = "Select";
			$this->query ="";
			$this->query = $this->query." SELECT ".$column;
			return $this;
		}
		function prep_array($array)
	{
		$counter=0;$new=array();$temp2=array();$keys=array_keys($array);while($counter<$this->count){$temp2=array();$counter2=0;while($counter2<count($keys)){$temp2[$keys[$counter2]]=mysql_result($this->result, $counter, $keys[$counter2]);$counter2++;}array_push($new, $temp2);$counter++;}
		return $new;
	}
		function from($table)
		{
			$this->query = $this->query." FROM ".$table;
			return $this;
		}
		function query($query)
		{
			$this->query = $query;
			return $this;
		}
		function insert_into($table)
		{
			$this->query_type = "Non-Select";
			$this->query = "";
			$this->query = $this->query." INSERT INTO ".$table;
			return $this; 
		}
		function columns($columns = array())
		{
			$counter = 0;
			$keys = array_keys($columns);
			$cols= array();
			$values = array();
			// Populate the $column array
			while ($counter < count($columns))
			{
				array_push($cols, $keys[$counter]);
				$counter++;
			}
			//Populate the $values array.
			$counter = 0;
			while ($counter < count($columns))
			{
				array_push($values, $columns[$keys[$counter]]);
				$counter++;
			}
			$counter = 0;
			$columns1 = " (";
				while ($counter < count($cols))
				{					
					if ($counter !== 0 || $counter !== count($columns)-1)
					{
						$columns1 = $columns1.", ".$cols[$counter];
					}
					else
					{
						$columns1 = $columns1.$cols[$counter];
					}
					$counter++;
				}
			$columns1 = $columns1.") ";
			$columns1 = str_replace('(, ', '(', $columns1);
			$values1 = "VALUES (";
			$counter = 0;
			while ($counter < count($values))
			{
				if ($counter !== 0 || $counter !== count($columns)-1)
					{
						$values1 = $values1.", '".$values[$counter]."'";
					}
			$counter++;
			}
			$values1 = $values1.")";
			$values1 = str_replace("(, ", "(", $values1);
			$this->query = $this->query.$columns1.$values1;
			return $this;
		}
		function where($value1, $operator, $value2)
		{
			$this->query = $this->query." WHERE ".$value1." ".$operator." '".$value2."'";
			return $this;
		}
		function set($value1, $value2)
		{
			$this->query = $this->query." SET ".$value1." = '".$value2."'";
			return $this;
		}
		function limit($amount)
		{
			$this->query = $this->query." Limit ".$amount;
			return $this;
		}
		function and_set($value1, $value2)
		{
			$this->query = $this->query.", ".$value1." = '".$value2."'";
			return $this;
		}
		function offset($amount)
		{
			$this->query = $this->query." OFFSET ".$amount;
			return $this;
		}
		function delete_from($table)
		{
			$this->query_type = "Non-Select";
			$this->query = "";
			$this->query .= "DELETE FROM ".$table;
			return $this;
		} 
		function empty_table($table)
		{
			$this->query="";
			$this->query = "TRUNCATE TABLE `".$table."`";
			return $this;
		}
		function update($table)
		{
			$this->query_type = "Non-Select";
			$this->query = "UPDATE ".$table;
			return $this;
		}
		function and_where($value1, $operator, $value2)
		{
			$this->query = $this->query." AND ".$value1." ".$operator." '".$value2."'";
			return $this;
		}
		function _or($value1, $operator, $value2)
		{
			$this->query = $this->query." OR ".$value1." ".$operator." '".$value2."'";
			return $this;
		}
		function left_join($table)
		{
			$this->query = $this->query." LEFT JOIN ".$table;
		}
		function _on($value1, $operator, $value2)
		{
			$this->query = $this->query." ON ($value1.$operator.$value2)";
		}
		function set_non_select($debug)
		{
			if($debug)
			{
				$this->result = mysql_query($this->query);
				$this->count = mysql_affected_rows($this->connection);
				if (strlen(mysql_error($this->connection)) > 1)
				{
					$this->throw_error();
				}
				else
				{
					echo "\n Debug info: \n Query: ".$this->query."\n Affected Rows: ".$this->count."<br>";
				}
			}
			else
			{
				$this->result = mysql_query($this->query);
				$this->count = mysql_affected_rows();	
			}
			
		}
		function set_select($debug)
		{
			if($debug)
			{
				$this->result = mysql_query($this->query);
				$this->count = mysql_num_rows($this->result);
				$this->data = mysql_fetch_array($this->result, $this->result_type);
				if (strlen(mysql_error($this->connection)) > 1)
				{
					$this->throw_error();
				}
				else
				{
					echo '<div class="container">';
					echo '<div class="alert alert-info span10 offset1">';
					echo "<center><h4>Database Query Debugging Information</h4><hr/></center><strong>Query:</strong> ".$this->query." <br /> <strong>Returned Rows:</strong> ".$this->count." <br/><strong> Query Type:</strong> Select<br/>";
					echo mysql_error();
					echo '<a href="#" data-dismiss="alert" style="background-color: blue" class="close">×</a></div></div>';
				}
			}
			else
			{
				$this->result = mysql_query($this->query);
				$this->count = mysql_num_rows($this->result);
				$this->data = mysql_fetch_array($this->result, $this->result_type);
			}
			
		}
		function order_by( $column = "id", $direction = "Forward")
		{
			if($direction == "Forward")
			{$dir="ASC";}else { $dir="DESC"; }
			$this->query = $this->query. " ORDER BY ".$column." ".$dir;
			return $this;
		}
		function sanitize($input, $allowed_tags = FALSE)
		{
			$input_with_tags = $input;
			//$input = strip_tags($input, $allowed_tags);
			$input = str_replace("<script>", "", $input);
			$input = str_replace("\r\n", "", $input);
			$input = mysql_real_escape_string($input);
			$input = str_replace("'", "`", $input);
			return $input;
		}
		function unsanitize($input)
		{
			$input = str_replace("`", "'", $input);
			$input = htmlspecialchars_decode($input);
			return $input;
		}
		function smarty_clean_result($data)
		{
			$value = array();
			//mysql_data_seek($this->result);
			mysql_fetch_assoc($this->result);
			$count = 0;
			while ($count < $this->count)
			{
				array_push($value, mysql_fetch_array($this->result));
				$count++;
			}
		//	{
 		//		$value[] = $line;
		//	}
			return $value;
		}
		function pretty_array($data)
		{
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
		function close()
		{
			mysql_close($this->connection);
		}
	}
?>