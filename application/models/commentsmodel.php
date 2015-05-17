<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


/**
  * comments model
  */
class commentsmodel extends CI_Model
{
    private $usersTable = 'users';
    private $commentsTable = 'comments';
    private $postsTable = 'posts';
	
	public function __construct()
    {
        parent::__construct();
    }
	
	/**
	 * get posts
	 * param : @recordsPerPage - total records to be displayed
	 * param : @offset
	 * param : @postId - displays all comments corresponding to a single post
	**/
	public function getAllCommentsByPost($recordsPerPage=10, $offset=0, $postId=NULL)
	{
		if(!empty($postId)) {
			$this->db->select('c.id, c.description, u.name AS createdByName, c.user_id AS commentedById');
			$this->db->select("DATE_FORMAT(c.date_created, '%b %d, %Y %h:%i%p') AS date_created", FALSE);
			$this->db->from($this->commentsTable.' AS c');
			$this->db->join($this->usersTable.' AS u', 'c.user_id=u.id', 'left');
			$this->db->where('c.post_id', $postId);
			$this->db->order_by('c.date_created', 'DESC');
			$this->db->limit((int)$recordsPerPage, (int)$offset);
			return $this->db->get()->result_array();
		}
	}
	
	/**
	 * get total count of posts
	 * param : @post_id - displays all comments corresponding to a single post
	**/
	public function totalCommentsCountByPost($postId=NULL)
	{
		if(!empty($postId)) {
			$this->db->select('c.id');
			$this->db->from($this->commentsTable.' AS c');
			$this->db->where('c.post_id', $postId);
			return $this->db->get()->num_rows();
		}
	}
	
	/**
	 * get comment by id
	 * param : @commentId
	**/
	public function getCommentById($commentId=NULL)
	{
		if(!empty($commentId)) {
			$this->db->select('c.id, c.description, u.name AS createdByName, c.user_id AS commentedById, p.user_id AS postCreatedById');
			$this->db->select("DATE_FORMAT(c.date_created, '%b %d, %Y %h:%i%p') AS date_created", FALSE);
			$this->db->from($this->commentsTable.' AS c');
			$this->db->join($this->usersTable.' AS u', 'c.user_id=u.id', 'left');
			$this->db->join($this->postsTable.' AS p', 'c.post_id=p.id', 'left');
			$this->db->where('c.id', $commentId);
			return $this->db->get()->row_array();
		}
	}
	
}
/* End of file commentsmodel.php */
/* Location: ./application/models/commentsmodel.php */
?>