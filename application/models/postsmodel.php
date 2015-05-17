<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
  * posts model
  */
class postsmodel extends CI_Model
{
    private $usersTable = 'users';
    private $postsTable = 'posts';
	
	public function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * get posts
	 * param : @recordsPerPage - total records to be displayed
	 * param : @offset
	 * param : @userId - if passed, displays all posts created by single user
	**/
	public function getAllPosts($recordsPerPage=10, $offset=0, $userId=NULL)
	{
		$this->db->select('p.id, p.title, p.description, u.name AS createdByName');
		$this->db->select("DATE_FORMAT(p.date_created, '%b %d, %Y %h:%i%p') AS date_created", FALSE);
		$this->db->from($this->postsTable.' AS p');
		$this->db->join($this->usersTable.' AS u', 'p.user_id=u.id', 'left');
		if(!empty($userId)) {
			$this->db->where('p.user_id', $userId);
		}
		$this->db->order_by('p.date_created', 'DESC');
		$this->db->limit((int)$recordsPerPage, (int)$offset);
		return $this->db->get()->result_array();
	}
	
	/**
	 * get total count of posts
	 * param : @userId - if passed, displays all posts created by single user
	**/
	public function totalPostsCount($userId=NULL)
	{
		$this->db->select('p.id');
		$this->db->from($this->postsTable.' AS p');
		if(!empty($userId)) {
			$this->db->where('p.user_id', $userId);
		}
		return $this->db->get()->num_rows();
	}
	
	/**
	 * get post by id
	 * param : @postId
	**/
	public function getPostById($postId=NULL)
	{
		if(!empty($postId)) {
			$this->db->select('p.id, p.title, p.description, p.user_id AS createdById, u.name AS createdByName');
			$this->db->select("DATE_FORMAT(p.date_created, '%b %d, %Y %h:%i%p') AS date_created", FALSE);
			$this->db->from($this->postsTable.' AS p');
			$this->db->join($this->usersTable.' AS u', 'p.user_id=u.id', 'left');
			$this->db->where('p.id', $postId);
			return $this->db->get()->row_array();
		}
	}
	
}
/* End of file postsmodel.php */
/* Location: ./application/models/postsmodel.php */
?>