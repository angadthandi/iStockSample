<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author : Angad Thandi
 * posts controller is used to define methods that provide posts related functionality
**/

require_once 'BaseController.php';

class PostsController extends BaseController {

	/**
	 * constructor
	**/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('postsmodel');
	}
    
    /**
	 * This function is meant for creating new posts.
	**/
	public function createNewPost()
    {
		$loggeduser = parent::getLoggedUser();
		
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::Create New Post';
   		
		$data['msg'] = '';
		
   		if($this->input->post('title')) {
			$title = strip_tags(trim($this->input->post('title',TRUE)));
			$description = strip_tags(trim($this->input->post('description',TRUE)));
			
			if(!empty($title) && !empty($description)) {
				$data=array(
						'user_id'=>$loggeduser['id'],
						'title'=>$title,
						'description'=>$description,
						'date_created'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('posts',$data);
				
				header('location:'.$this->config->item('base_url').'dashboard'); exit;
			} else {
				$data['msg'] = 'Please enter all required fields.';
				$this->load->view('front/base/header', $headerData);
				$this->load->view('front/posts/create', $data);
				$this->load->view('front/base/footer', $footerData);
			}
		}
		else {
			$this->load->view('front/base/header', $headerData);
			$this->load->view('front/posts/create', $data);
			$this->load->view('front/base/footer', $footerData);
		}
    }
	
	/**
	 * edit post
	**/
	public function editPost($encPostId=NULL)
	{
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::Edit Post';
		
		$data['loggeduser'] = unserialize($this->session->userdata('loggeduser'));
		
		$postId = parent::_fnDecrypt(urldecode($encPostId), $this->qrEncryptionKey);
		
		$postArr = $this->postsmodel->getPostById($postId);
		if(empty($postArr)) {
			echo 'Invalid Post!'; exit;
		}
		$data['post'] = $postArr;
		
		if($this->input->post('title')) {
			$title = strip_tags(trim($this->input->post('title',TRUE)));
			$description = strip_tags(trim($this->input->post('description',TRUE)));
			
			if(!empty($title) && !empty($description)) {
				$data=array(
						'title'=>$title,
						'description'=>$description
					);
				$this->db->where('id',$postId);
				$this->db->update('posts',$data);
				$this->session->set_flashdata('notification', 'Post Updated.');
				header('location:'.$this->config->item('base_url').'viewpost/'.$encPostId); exit;
			} else {
				$this->session->set_flashdata('notification', 'Please enter all required fields.');
				header('location:'.$this->config->item('base_url').'editpost/'.$encPostId);
			}
		}
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/posts/edit', $data);
		$this->load->view('front/base/footer', $footerData);
	}
	
	/**
	 * view single post
	**/
	public function viewPost($encPostId=NULL, $curr_page=0)
	{
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::View Post';
		
		$data['loggeduser'] = unserialize($this->session->userdata('loggeduser'));
		
		$postId = parent::_fnDecrypt(urldecode($encPostId), $this->qrEncryptionKey);
		
		$postArr = $this->postsmodel->getPostById($postId);
		if(empty($postArr)) {
			echo 'Invalid Post!'; exit;
		}
		$data['post'] = $postArr;
		
		// get comments for current post
		
		$this->load->model('commentsmodel');
		
		$offset = (int)$this->uri->segment(3);
		$total_rows = $this->commentsmodel->totalCommentsCountByPost($postId);
		
		$config['base_url'] = $this->config->item('base_url').'viewpost/'.$this->uri->segment(2).'/';
		$config['total_rows'] = $total_rows;
		$config['per_page'] = RECORDS_PER_PAGE;
		$config['cur_page'] = $curr_page;
		$config['full_tag_open'] = '<ul class="pagination">';
		$config['full_tag_close'] = '</ul>';
		$config['cur_tag_open'] = '<li class="active"><a href="javascript:void(0);">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		
		$data['comments'] = $this->commentsmodel->getAllCommentsByPost(RECORDS_PER_PAGE, $offset, $postId);
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$data['pager'] = $this->pagination->create_links();
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/posts/view', $data);
		$this->load->view('front/base/footer', $footerData);
	}
    
    /**
	 * This function is meant for deleting a post
	**/
	public function deletePost($encPostId=NULL)
	{
		$loggeduser = NULL;
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		if(!empty($loggeduser)){
			$postId = parent::_fnDecrypt(urldecode($encPostId), $this->qrEncryptionKey);
			if(!empty($postId)) {
				// check if user has rights to delete a post, i.e. the post has been created by the user
				$postArr = $this->postsmodel->getPostById($postId);
				if(!empty($postArr) && $loggeduser['id']==$postArr['createdById']) {
					$this->db->where('id', $postId);
					$this->db->delete('posts');
					$status = 1;
				}
			}
		}
		$this->session->set_flashdata('notification', 'Post Deleted.');
		header('location:'.$this->config->item('base_url').'dashboard');
	}
	
}

/* End of file PostsController.php */
/* Location: ./application/controllers/PostsController.php */