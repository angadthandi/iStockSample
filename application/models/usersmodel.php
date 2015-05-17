<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
  * users model
  */
class usersmodel extends CI_Model
{
    private $usersTable = 'users';
    private $userTypesTable = 'user_types';
	
	public function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * verify login
	**/
	public function verifyUser($email = NULL, $password = NULL)
    {
    	if(!empty($email) && !empty($password)) {
			$this->db->select('u.id, u.name, u.email, u.user_type_id');
			$this->db->from($this->usersTable.' AS u');
			$this->db->where('u.email', $email);
			$this->db->where('u.password', $password);
			return $this->db->get()->row_array();
		}
    }
	
	/**
	 * get user detail by id
	**/
	public function getUserById($userId=NULL)
	{
		if(!empty($userId)) {
			$this->db->select('u.id, u.user_type_id, u.email, ut.user_type AS userType');
			$this->db->select("DATE_FORMAT(u.date_created, '%d-%b-%Y') AS date_created", FALSE);
			$this->db->from($this->usersTable.' AS u');
			$this->db->join($this->userTypesTable.' AS ut', 'u.user_type_id=ut.id', 'left');
			$this->db->where('u.id', $userId);
			return $this->db->get()->row_array();
		}
	}
	
	/**
	 * is valid emailId
	**/
	public function isValidEmailId($email=NULL)
	{
		if(!empty($email)) {
			$this->db->select('u.id');
			$this->db->from($this->usersTable.' AS u');
			$this->db->where('u.email', $email);
			return $this->db->get()->row_array();
		}
	}
	
	/**
	 * get all user types
	**/
	public function getAllUserTypes()
	{
		$this->db->select('ut.id, ut.user_type');
		$this->db->from($this->userTypesTable.' AS ut');
		return $this->db->get()->result_array();
	}
	
	/**
	 * get all users
	 * param : @recordsPerPage - total records to be displayed
	 * param : @offset
	 * param : @userId - if passed, user will not be displayed in the list, for logged in user
	**/
	public function getAllUsers($recordsPerPage=10, $offset=0, $userId=NULL)
	{
		$this->db->select('u.id, u.user_type_id, u.name, u.email, ut.user_type AS userType');
		$this->db->select("DATE_FORMAT(u.date_created, '%d-%b-%Y') AS date_created", FALSE);
		$this->db->from($this->usersTable.' AS u');
		$this->db->join($this->userTypesTable.' AS ut', 'u.user_type_id=ut.id', 'left');
		if(!empty($userId)) {
			$this->db->where('u.id !=', $userId);
		}
		$this->db->order_by('u.date_created', 'DESC');
		$this->db->limit((int)$recordsPerPage, (int)$offset);
		return $this->db->get()->result_array();
	}
	
	/**
	 * get total count of users
	**/
	public function totalUsersCount($userId=NULL)
	{
		$this->db->select('u.id');
		$this->db->from($this->usersTable.' AS u');
		if(!empty($userId)) {
			$this->db->where('u.id !=', $userId);
		}
		return $this->db->get()->num_rows();
	}
	
}
/* End of file usersmodel.php */
/* Location: ./application/models/usersmodel.php */
?>