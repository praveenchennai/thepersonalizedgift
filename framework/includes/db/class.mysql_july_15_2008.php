<?php

/**
 * Database FrameWork
 *
 * @author sajith
 * @package NewAgeSME FrameWork
 */

define('OBJECT','OBJECT',true);
define('ARRAY_A','ARRAY_A',true);
define('ARRAY_N','ARRAY_N',true);

/**
 * Database Class
 *
 */
class DB
{

    var $trace           = false;  // same as $debug_all
    var $debug_all       = false;  // same as $trace
    var $debug_called    = false;
    var $vardump_called  = false;
    var $show_errors     = true;
    var $num_queries     = 0;
    var $last_query      = null;
    var $last_error      = null;
    var $col_info        = null;
    var $captured_errors = array();
    var $dbuser 		 = false;
    var $dbpassword 	 = false;
    var $dbname 		 = false;
    var $dbhost 		 = false;
    var $cache_dir 		 = false;
    var $cache_queries 	 = false;
    var $cache_inserts 	 = false;
    var $use_disk_cache  = false;
    var $cache_timeout 	 = 24; 		// hours

    
    var $ajax_pag_result;
	var $ajax_pag_anchors;
	var $ajax_pag_total;

    /**
	 * Constructor - allow the user to perform a qucik connect at the
	 * same time as initialising the class
	 *
	 * @param string $dbuser
	 * @param string $dbpassword
	 * @param string $dbname
	 * @param string $dbhost
	 * @return DB
	 */
    function DB($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
    {
        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->dbname = $dbname;
        $this->dbhost = $dbhost;
    }

    /**
	 * Short hand way to connect to mySQL database server
	 * and select a mySQL database at the same time
	 *
	 * @param string $dbuser
	 * @param string $dbpassword
	 * @param string $dbname
	 * @param string $dbhost
	 * @return bool
	 */
    function quick_connect($dbuser='', $dbpassword='', $dbname='', $dbhost='localhost')
    {
        $return_val = false;
        if ( ! $this->connect($dbuser, $dbpassword, $dbhost,true) ) ;
        else if ( ! $this->select($dbname) ) ;
        else $return_val = true;
        return $return_val;
    }

    /**
	 * Try to connect to mySQL database server
	 *
	 * @param string $dbuser
	 * @param string $dbpassword
	 * @param string $dbhost
	 * @return bool
	 */
    function connect($dbuser='', $dbpassword='', $dbhost='localhost')
    {
        $return_val = false;

        // Must have a user and a password
        if ( ! $dbuser )
        {
            $this->register_error($this->get_error(1).' in '.__FILE__.' on line '.__LINE__);
            $this->show_errors ? trigger_error($this->get_error(1),E_USER_WARNING) : null;
        }
        // Try to establish the server database handle
        else if ( ! $this->dbh = @mysql_connect($dbhost,$dbuser,$dbpassword,true) )
        {
            $this->register_error($this->get_error(2).' in '.__FILE__.' on line '.__LINE__);
            $this->show_errors ? trigger_error($this->get_error(2),E_USER_WARNING) : null;
        }
        else
        {
            $this->dbuser = $dbuser;
            $this->dbpassword = $dbpassword;
            $this->dbhost = $dbhost;
            $return_val = true;
        }

        return $return_val;
    }

    /**
	 * Try to select a mySQL database
	 *
	 * @param string $dbname
	 * @return bool
	 */
    function select($dbname='')
    {
        $return_val = false;

        // Must have a database name
        if ( ! $dbname )
        {
            $this->register_error($this->get_error(3).' in '.__FILE__.' on line '.__LINE__);
            $this->show_errors ? trigger_error($this->get_error(3),E_USER_WARNING) : null;
        }

        // Must have an active database connection
        else if ( ! $this->dbh )
        {
            $this->register_error($this->get_error(4).' in '.__FILE__.' on line '.__LINE__);
            $this->show_errors ? trigger_error($this->get_error(4),E_USER_WARNING) : null;
        }

        // Try to connect to the database
        else if ( !@mysql_select_db($dbname,$this->dbh) )
        {
            // Try to get error supplied by mysql if not use our own
            if ( !$str = @mysql_error($this->dbh))
            $str = $this->get_error(5);

            $this->register_error($str.' in '.__FILE__.' on line '.__LINE__);
            $this->show_errors ? trigger_error($str,E_USER_WARNING) : null;
        }
        else
        {
            $this->dbname = $dbname;
            $return_val = true;
        }

        return $return_val;
    }

    /**
	 * Format a mySQL string correctly for safe mySQL insert
	 * (no mater if magic quotes are on or not)
	 *
	 * @param string $str
	 * @return string
	 */
    function escape($str)
    {
        return mysql_escape_string(stripslashes($str));
    }

    /**
	 * Return mySQL specific system date syntax
	 *
	 * @return string
	 */
    function sysdate()
    {
        return 'NOW()';
    }

    /**
	 * Perform mySQL query and try to detirmin result value
	 *
	 * @param string $query
	 * @return mixed
	 */
    function query($query)
    {

        // Initialise return
        $return_val = 0;

        // Flush cached values..
        $this->flush();

        // For reg expressions
        $query = trim($query);

        // Log how the function was called
        $this->func_call = "\$db->query(\"$query\")";

        // Keep track of the last query for debug..
        $this->last_query = $query;

        // Count how many queries there have been
        $this->num_queries++;

        // The would be cache file for this query
        $cache_file = $this->cache_dir.'/'.md5($query);

        // Try to get previously cached version
        if ( $this->use_disk_cache && file_exists($cache_file) )
        {
            // Only use this cache file if less than 'cache_timeout' (hours)
            if ( (time() - filemtime($cache_file)) > ($this->cache_timeout*3600) )
            {
                unlink($cache_file);
            }
            else
            {
                $result_cache = unserialize(file_get_contents($cache_file));

                $this->col_info = $result_cache['col_info'];
                $this->last_result = $result_cache['last_result'];
                $this->num_rows = $result_cache['num_rows'];

                // If debug ALL queries
                $this->trace || $this->debug_all ? $this->debug() : null ;

                return $result_cache['return_value'];
            }
        }

        // If there is no existing database connection then try to connect
        if ( ! isset($this->dbh) || ! $this->dbh )
        {
            $this->connect($this->dbuser, $this->dbpassword, $this->dbhost);
            $this->select($this->dbname);
        }

        // Perform the query via std mysql_query function..
        $this->result = @mysql_query($query,$this->dbh);
		//echo "query: ".$query."<br>";
        // If there is an error then take note of it..
        if ( $str = @mysql_error($this->dbh) )
        {
            $is_insert = true;
            $this->register_error($str);
            $this->show_errors ? trigger_error($str.'<br><b>Query</b>: '.$query.'<br>' ,E_USER_WARNING) : null;
            return false;
        }

        // Query was an insert, delete, update, replace
        $is_insert = false;
        if ( preg_match("/^(insert|delete|update|replace)\s+/i",$query) )
        {
			/**/
			/*$this->result = @mysql_query($query,$this->dbh);
			echo "Query: ".$query."<br>";
			echo "Rows Affected: ".mysql_affected_rows()."<br>";*/
			/**/
            $this->rows_affected = @mysql_affected_rows();

            // Take note of the insert_id
            if ( preg_match("/^(insert|replace)\s+/i",$query) )
            {
                $this->insert_id = @mysql_insert_id($this->dbh);
            }
			
            // Return number fo rows affected
            $return_val = $this->rows_affected;
        }
        // Query was a select
        else
        {

            // Take note of column info
            $i=0;
            while ($i < @mysql_num_fields($this->result))
            {
                $this->col_info[$i] = @mysql_fetch_field($this->result);
                $i++;
            }

            // Store Query Results
            $num_rows=0;
            while ( $row = @mysql_fetch_object($this->result) )
            {
                // Store relults as an objects within main array
                $this->last_result[$num_rows] = $row;
                $num_rows++;
            }

            @mysql_free_result($this->result);

            // Log number of rows the query returned
            $this->num_rows = $num_rows;

            // Return number of rows selected
			//echo "num_rows: ".$num_rows."<br>";
          //  exit;
			$return_val = $this->num_rows;
        }

        // disk caching of queries
        if ( $this->use_disk_cache && ( $this->cache_queries && ! $is_insert ) || ( $this->cache_inserts && $is_insert ))
        {
            if ( ! is_dir($this->cache_dir) )
            {
                $this->register_error("Could not open cache dir: $this->cache_dir");
                $this->show_errors ? trigger_error("Could not open cache dir: $this->cache_dir",E_USER_WARNING) : null;
            }
            else
            {
                // Cache all result values
                $result_cache = array
                (
                'col_info' => $this->col_info,
                'last_result' => $this->last_result,
                'num_rows' => $this->num_rows,
                'return_value' => $this->num_rows,
                );
                error_log ( serialize($result_cache), 3, $cache_file);
            }
        }

        // If debug ALL queries
        $this->trace || $this->debug_all ? $this->debug() : null ;

        return $return_val;

    }

    /**
	 * Register error class
	 *
	 * @param string $err_str
	 */
    function register_error($err_str)
    {
        // Keep track of last error
        $this->last_error = $err_str;

        // Capture all errors to an error array no matter what happens
        $this->captured_errors[] = array
        (
        'error_str' => $err_str,
        'query'     => $this->last_query
        );
    }

    /**
	 * Returns custom error
	 *
	 * @param int $num
	 * @return string
	 */
    function get_error($num) {
        switch($num) {
            case 1: return 'Require $dbuser and $dbpassword to connect to a database server'; break;
            case 2: return 'Error establishing mySQL database connection. Correct user/password? Correct hostname? Database server running?'; break;
            case 3: return 'Require $dbname to select a database'; break;
            case 4: return 'mySQL database connection is not active'; break;
            case 5: return 'Unexpected error while trying to select database'; break;
        }
    }

    /**
	 * To set mode to show error
	 *
	 */
    function show_errors()
    {
        $this->show_errors = true;
    }

    /**
	 * To hide errors
	 *
	 */
    function hide_errors()
    {
        $this->show_errors = false;
    }

    /**
	 *  Kill cached query results
	 *
	 */
    function flush()
    {
        // Get rid of these
        $this->last_result = null;
        $this->col_info = null;
        $this->last_query = null;
        $this->from_disk_cache = false;
    }

    /**
	 * Get one variable from the DB
	 *
	 * @param string $query
	 * @param int $x
	 * @param int $y
	 * @return mixed
	 */
    function get_var($query=null,$x=0,$y=0)
    {

        // Log how the function was called
        $this->func_call = "\$db->get_var(\"$query\",$x,$y)";

        // If there is a query then perform it if not then use cached results..
        if ( $query )
        {
            $this->query($query);
        }

        // Extract var out of cached results based x,y vals
        if ( $this->last_result[$y] )
        {
            $values = array_values(get_object_vars($this->last_result[$y]));
        }

        // If there is a value return it else return null
        return (isset($values[$x]) && $values[$x]!=='')?$values[$x]:null;
    }

    /**
	 * Get one row from the DB
	 *
	 * @param string $query
	 * @param mode $output
	 * @param int $y
	 * @return mixed
	 */
    function get_row($query=null,$output=OBJECT,$y=0)
    {

        // Log how the function was called
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";

        // If there is a query then perform it if not then use cached results..
        if ( $query )
        {
            $this->query($query);
        }

        // If the output is an object then return object using the row offset..
        if ( $output == OBJECT )
        {
            return $this->last_result[$y]?$this->last_result[$y]:null;
        }
        // If the output is an associative array then return row as such..
        elseif ( $output == ARRAY_A )
        {
            return $this->last_result[$y]?get_object_vars($this->last_result[$y]):null;
        }
        // If the output is an numerical array then return row as such..
        elseif ( $output == ARRAY_N )
        {
            return $this->last_result[$y]?array_values(get_object_vars($this->last_result[$y])):null;
        }
        // If invalid output type was specified..
        else
        {
            $this->print_error(" \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N");
        }

    }

    /**
	 * Function to get 1 column from the cached result set based in X index
	 *
	 * @param string $query
	 * @param int $x
	 * @return mixed
	 */
    function get_col($query=null,$x=0)
    {

        // If there is a query then perform it if not then use cached results..
        if ( $query )
        {
            $this->query($query);
        }

        // Extract the column values
        for ( $i=0; $i < count($this->last_result); $i++ )
        {
            $new_array[$i] = $this->get_var(null,$x,$i);
        }

        return $new_array;
    }

    /**
	 * Return the the query as a result set
	 *
	 * @param string $query
	 * @param mode $output
	 * @return mixed
	 */
    function get_results($query=null, $output = OBJECT)
    {

        // Log how the function was called
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        // If there is a query then perform it if not then use cached results..
        if ( $query )
        {
            $this->query($query);
        }

        // Send back array of objects. Each row is an object
        if ( $output == OBJECT )
        {
            return $this->last_result;
        }
        elseif ( $output == ARRAY_A || $output == ARRAY_N )
        {
            if ( $this->last_result )
            {
                $i=0;
                foreach( $this->last_result as $row )
                {

                    $new_array[$i] = get_object_vars($row);

                    if ( $output == ARRAY_N )
                    {
                        $new_array[$i] = array_values($new_array[$i]);
                    }

                    $i++;
                }

                return $new_array;
            }
            else
            {
                return null;
            }
        }
    }

    /**
	 * Get database query results pagewise
	 *
	 * @param string $query
	 * @param integer $page
	 * @param integer $limit
	 * @param string $params
	 * @param mode $output
	 * @return mixed
	 */
    function get_results_pagewise($query=null, $page, $limit = 20, $params='', $output=OBJECT, $orderBy='',$type='', $pageVarName="pageNo") {
        if ( $query ) {
		    $page 		= $page ? $page : 1;
            $limit 		= $limit ? $limit : 20;
            $from 		= ($page - 1) * $limit;
            $params 	= $params ? '&' . $params : '';
            $count 		= $this->query($query);
            
            if($orderBy) {
                $query .= " ORDER BY ".str_replace(":", " ", $orderBy);
                $params = $params . '&orderBy=' . $orderBy; 
            }
			if($limit!="All")
			{ 	 $query 	   .= " LIMIT $from, $limit"; 	}
			//echo  $query;
            $result 	= $this->get_results($query, $output);
			if($limit!="All")
			{  $numpages   = ceil($count/$limit); 	}
			else
			{  $numpages=1; }
			
            $str 		= "Page $page / $numpages&nbsp; &nbsp;<font color='#000000'>|</font>&nbsp; &nbsp;Matching Records : $count&nbsp; &nbsp;<font color='#000000'>|</font>&nbsp; &nbsp;";
			$str2 		= "";
            
            if($page > 1) {
                $str .= '<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".($page-1).$params.'&limit='.$limit).'">&laquo; Prev</a> &nbsp;<font color=\'#000000\'>|</font> &nbsp;';
                $str2.= '<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".($page-1).$params.'&limit='.$limit).'">&laquo; Prev</a> &nbsp;<font color=\'#000000\'>|</font> &nbsp;';
               
                $pre_link = "index.php?sess=".base64_encode("$pageVarName=".($page-1).$params.'&limit='.$limit);
            } else {
                $str .= "&laquo; Prev &nbsp;<font color='#000000'>|</font> &nbsp;";
                $str2 .= "&laquo; Prev &nbsp;<font color='#000000'>|</font> &nbsp;";
                $pre_link = "#";
            }
            $numLinks = 4;//enter only even numbers
            if($numpages > $numLinks) {
                $start = (($page - ($numLinks/2-1)) < 1) ? 1 : ($page - ($numLinks/2-1));
                $end   = (($start + ($numLinks-1)) > $numpages) ? $numpages : ($start + ($numLinks-1));
                $start = (($end - $start) < ($numLinks-1)) ? ($end - ($numLinks-1)) : $start;
            } else {
            	$start = 1;
            	$end   = $numpages;
            }
			$str1="";
            for($i=$start; $i<=$end; $i++) {
                if ($i == $page) {
                    $str .= $i . ' &nbsp;';
					$str1 .= $i . ' &nbsp;';
					$str2 .= $i . ' &nbsp;';
                } else {
                    $str .= '<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".$i.$params.'&limit='.$limit).'">'.$i.'</a> &nbsp;';
					$str1 .= '<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".$i.$params.'&limit='.$limit).'">'.$i.'</a> &nbsp;';
                    $str2 .= '<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".$i.$params.'&limit='.$limit).'">'.$i.'</a> &nbsp;';
					
                 }
				if($i<$end){
					$str1 .= '| &nbsp;';
				}
				
            }
			
            if($page < $numpages) {
                $str .= '<font color="#000000">|</font> &nbsp;<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".($page+1).$params.'&limit='.$limit).'">Next &raquo;</a> &nbsp;';
				$str1 .= '<font color="#000000">|</font> &nbsp;<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".($page+1).$params.'&limit='.$limit).'">Next &raquo;</a> &nbsp;';
				$str2 .= '<font color="#000000">|</font> &nbsp;<a class="toplinkpagi" href="?sess='.base64_encode("$pageVarName=".($page+1).$params.'&limit='.$limit).'">Next &raquo;</a> &nbsp;';
				
				$next_link = "index.php?sess=".base64_encode("$pageVarName=".($page+1).$params.'&limit='.$limit);
            } else {
			 	
                $str .= "<font color='#000000'>|</font> &nbsp;Next &raquo;";
                $str2 .= "<font color='#000000'>|</font> &nbsp;Next &raquo;";
				if($numpages>0){
					$str1 .= "<font color='#000000'>|</font> &nbsp;Next &raquo;";
				}
				$next_link = "#";
            }
            if($count == 0) {
                $str="";
                $str2="";
            } else {
            	$arr = array(5, 10, 20, 50, 100, 200,All);
           		$limitList = "<select class=combo onchange=\"window.location.href='?sess=".base64_encode("$pageVarName=1".$params)."&limit='+this.value;\">";
            	foreach ($arr as $val) {
            		$limitList .= "<option value='$val'".($val==$limit ? " selected" : "").">$val</option>";
            	}
           		$limitList .= "</select>";
            }
			if($type=='1')
				$str=$str2;
            return array($result, $str, $count, $limitList,$numpages,$pre_link,$next_link);
        }
    }

    /**
	 * Function to get column meta data info pertaining to the last query
	 *
	 * @param string $info_type
	 * @param int $col_offset
	 * @return mixed
	 */
    function get_col_info($info_type="name",$col_offset=-1)
    {

        if ( $this->col_info )
        {
            if ( $col_offset == -1 )
            {
                $i=0;
                foreach($this->col_info as $col )
                {
                    $new_array[$i] = $col->{$info_type};
                    $i++;
                }
                return $new_array;
            }
            else
            {
                return $this->col_info[$col_offset]->{$info_type};
            }

        }

    }

    /**
	 * Dumps the contents of any input variable to screen in a nicely
	 * formatted and easy to understand way - any type: Object, Var or Array
	 *
	 * @param mixed $mixed
	 */
    function vardump($mixed='')
    {

        echo "<p><table><tr><td bgcolor=ffffff><blockquote><font color=000090>";
        echo "<pre><font face=arial>";

        if ( ! $this->vardump_called )
        {
            echo "<font color=800080><b>Variable Dump..</b></font>\n\n";
        }

        $var_type = gettype ($mixed);
        print_r(($mixed?$mixed:"<font color=red>No Value / False</font>"));
        echo "\n\n<b>Type:</b> " . ucfirst($var_type) . "\n";
        echo "<b>Last Query</b> [$this->num_queries]<b>:</b> ".($this->last_query?$this->last_query:"NULL")."\n";
        echo "<b>Last Function Call:</b> " . ($this->func_call?$this->func_call:"None")."\n";
        echo "<b>Last Rows Returned:</b> ".count($this->last_result)."\n";
        echo "</font></pre></font></blockquote></td></tr></table>";
        echo "\n<hr size=1 noshade color=dddddd>";

        $this->vardump_called = true;

    }

    /**
	 * Dumps the contents of any input variable to screen in a nicely
	 * formatted and easy to understand way - any type: Object, Var or Array
	 *
	 * @param mixed $mixed
	 */
    function dumpvar($mixed)
    {
        $this->vardump($mixed);
    }

    /**
	 * Displays the last query string that was sent to the database & a
	 * table listing results (if there were any).
	 * (abstracted into a seperate file to save server overhead).
	 *
	 */
    function debug()
    {

        echo "<blockquote>";

        // Only show credits once..
        if ( ! $this->debug_called )
        {
            echo "<font color=800080 face=arial size=2><b>Debug..</b></font><p>\n";
        }

        if ( $this->last_error )
        {
            echo "<font face=arial size=2 color=000099><b>Last Error --</b> [<font color=000000><b>$this->last_error</b></font>]<p>";
        }

        if ( $this->from_disk_cache )
        {
            echo "<font face=arial size=2 color=000099><b>Results retrieved from disk cache</b></font><p>";
        }


        echo "<font face=arial size=2 color=000099><b>Query</b> [$this->num_queries] <b>--</b> ";
        echo "[<font color=000000><b>$this->last_query</b></font>]</font><p>";

        echo "<font face=arial size=2 color=000099><b>Query Result..</b></font>";
        echo "<blockquote>";

        if ( $this->col_info )
        {

            // =====================================================
            // Results top rows

            echo "<table cellpadding=5 cellspacing=1 bgcolor=555555>";
            echo "<tr bgcolor=eeeeee><td nowrap valign=bottom><font color=555599 face=arial size=2><b>(row)</b></font></td>";


            for ( $i=0; $i < count($this->col_info); $i++ )
            {
                echo "<td nowrap align=left valign=top><font size=1 color=555599 face=arial>{$this->col_info[$i]->type} {$this->col_info[$i]->max_length}</font><br><span style='font-family: arial; font-size: 10pt; font-weight: bold;'>{$this->col_info[$i]->name}</span></td>";
            }

            echo "</tr>";

            // ======================================================
            // print main results

            if ( $this->last_result )
            {

                $i=0;
                foreach ( $this->get_results(null,ARRAY_N) as $one_row )
                {
                    $i++;
                    echo "<tr bgcolor=ffffff><td bgcolor=eeeeee nowrap align=middle><font size=2 color=555599 face=arial>$i</font></td>";

                    foreach ( $one_row as $item )
                    {
                        echo "<td nowrap><font face=arial size=2>$item</font></td>";
                    }

                    echo "</tr>";
                }

            } // if last result
            else
            {
                echo "<tr bgcolor=ffffff><td colspan=".(count($this->col_info)+1)."><font face=arial size=2>No Results</font></td></tr>";
            }

            echo "</table>";

        } // if col_info
        else
        {
            echo "<font face=arial size=2>No Results</font>";
        }

        echo "</blockquote></blockquote><hr noshade color=dddddd size=1>";


        $this->debug_called = true;
    }

    /**
	 * To execute an insert query
	 *
	 * @param string $table_name
	 * @param mixed $array
	 * @return integer
	 */
    function insert($table_name, $field_array) {
	
        $str = "INSERT INTO `$table_name` SET ";
        if( is_array($field_array) ) {
            foreach ($field_array as $field=>$value) {
                $str .= "`$field` = '$value',";
            }
            $str = substr($str, 0, -1);		
			
            $this->query($str);

            return $this->insert_id;
        } else {
            return false;
        }
    }

    /**
	 * To execute an insert query
	 *
	 * @param string $table_name
	 * @param mixed $field_array
	 * @param string $condition
	 * @return integer
	 */
    function update($table_name, $field_array, $condition="") {
	
	
        $str = "UPDATE `$table_name` SET ";
        if( is_array($field_array) ) {
            foreach ($field_array as $field=>$value) {
                $str .= "`$field` = '$value',";
            }
            $str = substr($str, 0, -1);
            if( $condition ) $str .= " WHERE " . $condition;
			//echo "<br>";
			//echo "str: ".$str."<br>";
             $affected = $this->query($str);
			//echo "affected: ".$affected."<br>";
            return $affected;
        } else {
            return false;
        }
    }
    
    
    /**
     * AJAX PAGINATION 
     * Aneesh Aravindan
     */
    function get_results_pagewise_ajax($query=null,$page,$limit=10, $params='',$output=OBJECT, $orderBy='',$numpadtype='', $pageVarName="pageNo")
	{	
		
		
		if ( $query ) {	
			$page 		= $page ? $page : 0;  			# Starting Page
			$limit 		= $limit ? $limit : 10;			# Record Limit
			$params 	= $params ? '&' . $params : ''; # Not needed if AJAX Call		

			$norepeat = 3;
			
			$CURR_PAGE  = $page;
			
			
			$numrows	=	$this->query($query);		# Getting count of the Result
			
			if($numrows<1)
			return false;
			
			if($orderBy) {								# Appending OrderBY Clause
	            $query .= " ORDER BY ".str_replace(":", " ", $orderBy);
	            $params = $params . '&orderBy=' . $orderBy; 
	        }
	        
	
		
	        
			if($limit<=$numrows)						# Append if LIMIT Needed
			{ 	 
				$query 	   .= " limit $page, $limit";	
			}
			# Get All Results		
			$this->ajax_pag_result	=	$this->get_results($query, $output);
			
			$orderBy = '"'.$orderBy.'"';
			
			$next		=	$page+$limit;
			$var		=	((intval($numrows/$limit))-1)*$limit;
			$page_showing	=	intval($page/$limit)+1;
			$total_page	=	ceil($numrows/$limit);
								
			
			if($numrows % $limit != 0){
				$last = ((intval($numrows/$limit)))*$limit;
			}else{
				$last = ((intval($numrows/$limit))-1)*$limit;
			}
			$previous = $page-$limit;
			
			
			if($previous < 0){
				
				if($numpadtype==1){
					$anc = "Previous | 1 | ";
				}else{
					$anc = "First l Previous l ";
				}
				
				$pre_link = "Previous";
			}else{
				
				if($numpadtype==1) {
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($previous,$limit,$orderBy,1);void(0);'>Previous l </a>";
					if($page_showing>$norepeat+2)
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination(0,$limit,$orderBy,1);void(0);'>1... l </a>";
					else
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination(0,$limit,$orderBy,1);void(0);'>1 l </a>";
				}else {
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination(0,$limit,$orderBy,1);void(0);'>First l </a>";
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($previous,$limit,$orderBy,1);void(0);'>Previous l </a>";
				}
				
				$pre_link = "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($previous,$limit,$orderBy,1);void(0);'>&lt;&lt;Previous </a>";
			}
			
			
			$j = 1;
			for($i=$page_showing; $i>1; $i--){
				$fpreviousPage = $i-1;
				$page = ceil($fpreviousPage*$limit)-$limit;
				
				if($numpadtype==1) {
					if($fpreviousPage!=1)
					$anch = "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($page,$limit,$orderBy,1);void(0);'>$fpreviousPage l </a>".$anch;
				}else{
					$anch = "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($page,$limit,$orderBy,1);void(0);'>$fpreviousPage l </a>".$anch;
				}
				
				if($j == $norepeat) break;
				$j++;
			}
			$anc .= $anch;
			
			if($numpadtype==1){
				if($page_showing!=1 && $page_showing!=$total_page)
				$anc .= $page_showing ."l ";
			}else{
				$anc .= $page_showing ."l ";
			}
			
			$j = 1;
			for($i=$page_showing; $i<$total_page; $i++){
				$fnextPage = $i+1;
				$page = ceil($fnextPage*$limit)-$limit;
				
								
				if($numpadtype==1) {
					if ($fnextPage!=$total_page)
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($page,$limit,$orderBy,1);void(0);'>$fnextPage l </a>";
				}else{
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($page,$limit,$orderBy,1);void(0);'>$fnextPage l </a>";
				}
				
				if($j==$norepeat) break;
				$j++;
			}
			############################################################
			if($next >= $numrows){
				if($numpadtype==1) {
					if($page_showing!=1)
					$anc .= "$total_page | Next ";
					else 
					$anc .= "Next ";
				}else{
					$anc .= "Next l Last ";
				}
				$next_link="Next";
			}else{
				
				if($numpadtype==1) {
					if($page_showing> ($total_page-($norepeat+2)) )
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($last,$limit,$orderBy,1);void(0);'>$total_page l </a>";
					else 
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($last,$limit,$orderBy,1);void(0);'>...$total_page l </a>";
					
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($next,$limit,$orderBy,1);void(0);'>Next</a>";
				}else {
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($next,$limit,$orderBy,1);void(0);'>Next l </a>";
					$anc .= "<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($last,$limit,$orderBy,1);void(0);'>Last</a>";	
				}
				
				$next_link="<a class='toplinkpagi' style='text-decoration:none' href='javascript:pagination($next,$limit,$orderBy,1);void(0);'>Next&gt&gt;</a>";
			}
			$this->ajax_pag_anchors = $anc;
			
			$this->ajax_pag_total = "<svaluestrong>Page : $page_showing <i> Of  </i> $total_page . Total Records Found: $numrows</svaluestrong>";
			
			
			if ($numrows>0) {
				$arr = array(15=>15, 30=>30);  #, 45=>45, 60=>60, 90=>90, 150=>150,($numrows+1)=>All
           		$limitList = "<select id=numRecRes class=inputelement onchange='javascript:pagination(0,this.value,$orderBy,1);void(0);'>";
            	foreach ($arr as $k=>$val) {
            		$limitList .= "<option value='$k'".($k==$limit ? " selected" : "").">$val</option>";
            	}
           		$limitList .= "</select>";
			}
			
			$pre_next_link = $pre_link . "&nbsp;" .$next_link;
			
			$this->ajax_pag_anchors = $this->ajax_pag_anchors."<span id=\"CURR_AJAX_PAGE\" style=\"display:none\">".$CURR_PAGE."</span>";
			
		    return array($this->ajax_pag_result, $this->ajax_pag_anchors, $numrows,$limitList,$this->ajax_pag_total,$pre_next_link,$next_link);
		} 
	}
	
	
	
	
}

?>