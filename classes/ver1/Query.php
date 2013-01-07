<?
class Query{
 	
	public $db_link;

    public function __construct($debug=false){
        
        $this->db_link = $this->db_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, $debug);
    }
        
	function db_get_link($db_host, $db_user, $db_pass, $debug=false) {
		// Open Connection
		$db_link=mysql_connect($db_host,$db_user,$db_pass);
		if ($db_link) {
			return $db_link;
		} else {
			if ($debug) {
				die('ERROR: cannot connect to MySQL server.');
			} else {
				return false;
			}
		}
	}

	function db_select_db($db_link, $db_name, $debug=false) {
		// Select Database
		if (mysql_select_db($db_name,$db_link)) {
		   return true;
		} elseif ($debug) { 	 
			die('Could not select the DB.');
		} else {
			return false;
		}
	}

	function db_connect($db_host, $db_user, $db_pass, $db_name, $debug=false) {
		// Open Connection
		$db_link=$this->db_get_link ($db_host,$db_user,$db_pass,$debug);
		if ($db_link) {
			if ($this->db_select_db ($db_link, $db_name, $debug)) {
				// Select unicode encoding.
				mysql_query("SET NAMES 'utf8'");           
			} else {
				return false;
			}
		} else {
			return false;
		}
		return $db_link;
	}

	function run_query ($query,$debug=false)
	{
		$result_set = mysql_query($query,$this->db_link);
		if ($debug) {
			if (!$result_set) {
				return '<div dir="ltr">'.chr(13).mysql_error().'<br>'.chr(13).$query.chr(13).'</div>';
				exit();
			}
		}
		return $result_set;
	}
	function run_special_query ($query,$debug=false){
			$result = $this->run_query($query,$debug);
	  		return $this->createResultsArray($result);
	}
	function quote_smart($value){
		// Quote variable to make safe
		
		 // Stripslashes
		 if (get_magic_quotes_gpc()) {
			 $value = stripslashes($value);
		 }
		   // Quote if not a number or a numeric string
		 if (!is_numeric($value)) {
		         $value = "'" . mysql_real_escape_string($value) . "'";
		 }
		 return $value;
	}
	
	function escape_smart($value)
	// Quote variable to make safe
	{
	   // Stripslashes
	   if (get_magic_quotes_gpc()) {
		 $value = stripslashes($value);
	   }
	   $value =  htmlentities($value, ENT_COMPAT, 'UTF-8');
	   $value =  mysql_real_escape_string($value);
	
	   return $value;
	}
	
	function delete($table, $where='', $limit='',$debug=false) {
	   if ($where)  $where = " where $where";
	   if ($limit)  $limit=" limit $limit";
	//echo "delete from $table $where $limit";
	   return  $this->run_query("delete from $table $where $limit",$debug);   
	}

	function setFields($fields) {
	//var_dump($fields);
	  $numFields = count($fields);
	  $i=0;
	  $setString='';
	  foreach($fields as $field=>$value) {
		 if (!is_int($value) && !($value=='now()'))  {
			  if (!$this->is_serial($value))  $value = $this->escape_smart($value);
			  $setString .= " `$field`='$value'";
		 } else {
			  $setString .= " `$field`=$value";
		 }
	
		 $setString.= ($i<$numFields-1)?',':'';
		 $i++;
	  }
	  return $setString;
	
	}
	function is_serial($string) {
		return (@unserialize($string) !== false);
	}
	function add($table, $fields,$debug=false) {
	  $query = "insert into $table set ";
	  $query .= $this->setFields($fields);
	  $result =  $this->run_query($query,$debug);
	  return mysql_insert_id();
	 
	}
	
	function update($table, $fields, $where, $limit,$debug=false) {
	  $query = "update $table set ";
	  $query .= $this->setFields($fields);
	  if ($where)  $query .= " WHERE $where ";
	  if ($limit)  $query .= " LIMIT $limit";
	
	  $result = $this->run_query($query,$debug);
	  return $result;
	}
	
	function createResultsArray($result) {
	  if ($result) {
		$res = array();
		$i=0;
		if (mysql_num_rows($result)>0) {
		while ($row = mysql_fetch_array($result)) {
		//   var_dump($row);echo "<br /><br />";
		  $res[$i] = $row;
		  $i++;
		}
		//  $res['count']=$i;
		return $res;
		}
		else return null;
	  }
	  else return null;
	}
	function getRows($table, $where, $orderby, $limit, $offset=null,$debug=false) {
	   if ($where)  $where = " where $where";
	   if ($limit)  $limit=" limit $limit";
	   if ($orderby)  $orderby = " order by $orderby";
	   if (!is_null($offset))  $offset = " offset $offset";
	   $query = "select * from `$table` $where $orderby $limit $offset"; 
	//echo $query."<br />";
	   $result = $this->run_query($query,$debug);
	   return $this->createResultsArray($result);
	}
	
	function getCount($table, $where, $orderby, $limit,$debug=false) {
	   if ($where)  $where = " where $where";
	   if ($limit)  $limit=" limit $limit";
	   if ($orderby)  $orderby = " order by $orderby";
	   $query = "select count(*) as num_records from $table $where $orderby $limit";
	//echo $query;
	   $result = $this->run_query($query,$debug);
	   $ret=$this->createResultsArray($result);
	   return $ret[0]['num_records'];
	}
	
	function getRow($table, $where,$debug=false) {
	  $rows = $this->getRows($table, $where, null, 1, null,$debug);
	   return $rows[0];
	}
	function find_value($table, $what_info,  $column, $id,$debug=false )
	{
	$id = $this->escape_smart($id);
	$q = "select $what_info as result_value from $table  where $column='$id' limit 1";
	$result = $this->run_query($q,$debug);
			if($result)
			{
			$ret=$this->createResultsArray($result);
	   		$return_val =  $ret[0]['result_value'];
			}
	return $return_val;
	}
	
	function GetSetting($setting_name,$language=null,$debug=false){
	$language = ($language!=null)?$language:0;
	$where = "`name`='$setting_name' AND `language`='$language'";
	$result =$this->getRow(PREFIX."site_settings", $where,$debug);
	return $result['value'];
	}
}

?>