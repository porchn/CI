<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// require Zend framework library for set DB workflow
require_once('Zend/Db.php');
require_once('Zend/Db/Expr.php');

/**
 * OKzeed "MY_Model" class 
 * 
 * This class set database configuration and parent query method
 * that extends from zend framework database library 
 * 
 * @category  	Model
 * @package   	Codeigniter[okzeed]
 * @author    	Rungsun Somanat <rungunsomanat@gmail.com>
 * @link 		http://www.okzeed.com
 */

class MY_Model extends CI_Model {

	public $tbl_prefix = null;				# table prefix name
	
	private $db_master;					# database group
	private static $_conn = null;		# connection varieable. All method use it for connect Database before query
	
	/**
	 * method construct
	 * set value of database group,table prefix name
	 *
	 * @access	public
	 */

	public function __construct(){
		require APPPATH."config/database".EXT;				
		$this->db_master = $active_group;
		$this->tbl_prefix = $db[$this->db_master]['dbprefix'];
	}
	
	// --------------------------------------------------------------------

	/**
	 * connect method
	 * plublic method for connect database
	 *
	 * @access	public
	 * @param	string database group
	 */

	public function connect($db_group=null)
	{	
		// set default database group
		if(!is_null($db_group))
			$this->db_master = $db_group;

		require APPPATH."config/database".EXT;	
		//set connect database params
		$params = array(
						'host' => $db[$this->db_master]['hostname'],			# host name
						'username' => $db[$this->db_master]['username'],		# database userbane
						'password' => $db[$this->db_master]['password'],		# database password
						'dbname' => $db[$this->db_master]['database']			# database name
					);
		
		
		$conn = Zend_Db::factory($db[$this->db_master]['dbdriver'], $params);						# set object for connect to database
		if ($db[$this->db_master]['char_set'] && $db[$this->db_master]['dbcollat'])
		{
			$conn->Query('SET character_set_results='.$db[$this->db_master]['char_set']);			# set database chaset
			$conn->Query('SET collation_connection='.$db[$this->db_master]['dbcollat']);			# set database collation
			$conn->Query('SET NAMES '.$db[$this->db_master]['char_set']);							# set database chaset name
		}
		self::$_conn = $conn;													#access object connect to public connect variable
	}
	
	// --------------------------------------------------------------------

	public function qoute($string)
	{
		$this->connect($this->db_master);
		return self::$_conn->quoteIdentifier($string);
	}
	
	public function quoteInto($text, $value, $type= null, $count=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->quoteInto($text, $value, $type, $count);
	}
	
	public function select()
	{	
		$this->connect($this->db_master);
		return self::$_conn->select();
	}
	
	public function fetchObject($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchObject($sql, $attrs);
	}

	public function fetchAll($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchAll($sql, $attrs);
	}
	
	public function fetchRow($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchRow($sql, $attrs);
	}

	public function fetchOne($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchOne($sql, $attrs);
	}
	
	public function fetchCol($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchCol($sql, $attrs);
	}
	
	public function fetchPair($sql, $attrs=null)
	{
		$this->connect($this->db_master);
		return self::$_conn->fetchPair($sql, $attrs);
	}
	
	/**
	 * fetchPage
	 * This function only work with Zend_db_Select statement
	 *
	 *
	 * @access	 public
	 * @param object (Zend_Db_Select)
	 * @param array
	 * @param integer
	 * @param integer
	 * @return	array
	 */
	public function fetchPage($sql, $attrs=array(), $page=1, $limit_per_page=20)
	{
		$page = ((int) $page < 1) ? 1 : $page;

		$sql->limitPage($page, $limit_per_page);
		$rows = self::$_conn->fetchAll($sql, $attrs);

		$sql->reset(Zend_Db_Select::COLUMNS);
		$sql->reset(Zend_Db_Select::ORDER);
		$sql->reset(Zend_Db_Select::LIMIT_COUNT);
		$sql->reset(Zend_Db_Select::LIMIT_OFFSET);

		$sql->columns('COUNT(*)');

		if ($sql->getPart(Zend_Db_Select::GROUP))
		{
			$records = self::$_conn->fetchAll($sql, $attrs);
			$row_count = count($records);
		}
		else
		{
			$row_count = self::$_conn->fetchOne($sql, $attrs);
		}
		
		if($limit_per_page <= 0) $limit_per_page = 20;
		
		$data = array(
			'rows' => $rows,
			'row_count' => $row_count,
			'limit_per_page' => $limit_per_page,
			'current_page' => $page,
			'total_page' => ceil($row_count/$limit_per_page)
		);
		
		return $data;
	}

	public function insert($table, $data)
	{
		$this->connect($this->db_master);
		if (self::$_conn->insert($table, $data))
		{
			return self::$_conn->lastInsertId();
		}
		return false;
	}

	public function update($table, $data, $where)
	{
		$this->connect($this->db_master);
		return self::$_conn->update($table, $data, $where);
	}
	
	public function delete($table, $where)
	{
		$this->connect($this->db_master);
		return self::$_conn->delete($table, $where);
	}
	
	public function expr($string)
	{
		return new Zend_Db_Expr($string);
	}

}

?>