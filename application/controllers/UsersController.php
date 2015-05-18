<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author : Angad Thandi
 * users controller is used to define methods that provide user related functionality
**/

require_once 'BaseController.php';

class UsersController extends BaseController {

	/**
	 * constructor
	**/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('usersmodel');
		$this->load->model('postsmodel');
	}

	/**
	 * Index/Home Page
	**/
	public function index($curr_page=0)
	{
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		
		$headerData['title'] = APP_NAME.'::HomePage';
		
		$data = array();
		$loggeduser = NULL; $loggeduserId = NULL;
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		if(!empty($loggeduser)) {
			$loggeduserId = $loggeduser['id'];
		}
		$data['loggeduser'] = $loggeduser;
		
		$offset = (int)$this->uri->segment(1);
		
		$total_rows = $this->postsmodel->totalPostsCount();
		
		$config['base_url'] = $this->config->item('base_url');
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
		
		$arr = array();
		$postsArr = $this->postsmodel->getAllPosts(RECORDS_PER_PAGE, $offset);
		if(!empty($postsArr)) {
			foreach($postsArr as $val) {
				$arr[] = array(
								'id' => urlencode(parent::_fnEncrypt($val['id'], $this->qrEncryptionKey)),
								'title' => $val['title'],
								'description' => $val['description'],
								'createdByName' => $val['createdByName'],
								'date_created' => $val['date_created']
							);
			}
		}
		$data['posts'] = $arr;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$data['pager'] = $this->pagination->create_links();
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/home', $data);
		$this->load->view('front/base/footer', $footerData);
	}

	/**
	 * Users Listing
	**/
	public function usersListing($curr_page=0)
	{
		$loggeduser = parent::getLoggedUser();
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		
		$headerData['title'] = APP_NAME.'::Users';
		
		$data = array();
		$loggeduserId = NULL;
		if(!empty($loggeduser)) {
			$loggeduserId = $loggeduser['id'];
		}
		$data['loggeduser'] = $loggeduser;
		
		$offset = (int)$this->uri->segment(2);
		
		$total_rows = $this->usersmodel->totalUsersCount($loggeduserId);
		
		$config['base_url'] = $this->config->item('base_url').'users/';
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
		
		$usersArr = $this->usersmodel->getAllUsers(RECORDS_PER_PAGE, $offset, $loggeduserId);
		$data['users'] = $usersArr;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$data['pager'] = $this->pagination->create_links();
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/users/usersListing', $data);
		$this->load->view('front/base/footer', $footerData);
	}
	
	/**
     * user login function
    **/
    private function _authUser($email=NULL, $pass=NULL)
    {
    	$status = -1;
		if(!empty($email) && !empty($pass)) {
			$userData = $this->usersmodel->verifyUser($email, md5($pass));
			if(!empty($userData)) {
				$this->session->set_userdata('loggeduser', serialize($userData));
				$status = 1;
			}
    	}
		return $status;
    }
	
	/**
	 * user login page
	**/
	public function login()
	{	
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::Login';
		$session_data = NULL;
   		$session_data = unserialize($this->session->userdata('loggeduser'));
   		if(!empty($session_data)) {
   			header('location:'.$this->config->item('base_url').'dashboard'); exit;
   		}
		
		if($this->input->post('submit')) {
			$email = $this->input->post('email');
			$pass = $this->input->post('password');
			$isUser = $this->_authUser($email, $pass);
			if($isUser == 1) {
				$loggedUser = unserialize($this->session->userdata('loggeduser'));
				if($loggedUser['user_type_id'] == 1){
					header('location:'.$this->config->item('base_url').'dashboard'); exit;
				} else{
					header('location:'.$this->config->item('base_url')); exit;
				}
			} else {
				$this->session->set_flashdata('notification', 'Invalid Email or Password.');
				header('location:'.$this->config->item('base_url').'login');
			}
		}
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/users/login');
		$this->load->view('front/base/footer', $footerData);
	}
	
	/**
	 * logout user
	**/
	public function logout()
	{
		$this->session->unset_userdata('loggeduser');
		header('location:'.$this->config->item('base_url').'login');
	}
    
    /**
	 * This function is meant for user registration.
	**/
	public function signup()
    {
		$session_data = NULL;
   		$session_data = unserialize($this->session->userdata('loggeduser'));
   		if(!empty($session_data)) {
   			header('location:'.$this->config->item('base_url').'dashboard'); exit;
   		}
		
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::Signup';
		
		$data['footer'] = $footerData;
   		
		$data['msg'] = '';
		$data['userTypes'] = $this->usersmodel->getAllUserTypes();
		
   		if($this->input->post('email')) {
			$name = strip_tags(trim($this->input->post('name',TRUE)));
			$email = strip_tags(trim($this->input->post('email',TRUE)));
			$password = strip_tags(trim($this->input->post('password',TRUE)));
			$userTypeId = strip_tags(trim($this->input->post('userType',TRUE)));
			
			if(!empty($name) && !empty($email) && !empty($password) && !empty($userTypeId)) {
				if ($this->usersmodel->isValidEmailId($email)) {
					$data['msg'] = 'This Email is already associated with an account.';
					$this->load->view('front/base/header', $headerData);
					$this->load->view('front/users/signup', $data);
					$this->load->view('front/base/footer', $footerData);
				}
				else {
					$data=array(
							'name'=>$name,
							'email'=>$email,
							'password'=>md5($password),
							'user_type_id'=>$userTypeId,
							'date_created'=>date('Y-m-d H:i:s')
						);
					$this->db->insert('users',$data);
					$userId = $this->db->insert_id();
					
					//log the user in
					$sessArr = array(
								'id' => $userId,
								'name' => $name,
								'email' => $email,
								'user_type_id' => $userTypeId
							);
					$this->session->set_userdata('loggeduser',serialize($sessArr));
					
					if($sessArr['user_type_id'] == 1){
						header('location:'.$this->config->item('base_url').'dashboard'); exit;
					} else{
						header('location:'.$this->config->item('base_url')); exit;
					}
				}
			} else {
				$data['msg'] = 'Please enter all required fields.';
				$this->load->view('front/base/header', $headerData);
				$this->load->view('front/users/signup', $data);
				$this->load->view('front/base/footer', $footerData);
			}
		}
		else {
			$this->load->view('front/base/header', $headerData);
			$this->load->view('front/users/signup', $data);
			$this->load->view('front/base/footer', $footerData);
		}
    }
	
	/**
	 * user dashboard - displays logged user posts
	**/
	public function dashboard($curr_page=0)
	{
		$loggeduser = parent::getLoggedUser();
		
		if($loggeduser['user_type_id'] != 1){
			header('location:'.$this->config->item('base_url')); exit;
		}
		
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$headerData['title'] = APP_NAME.'::Dashboard';
		
		$data = array();
		$data['loggeduser'] = $loggeduser;
		
		$offset = (int)$this->uri->segment(2);
		
		$total_rows = $this->postsmodel->totalPostsCount($loggeduser['id']);
		
		$config['base_url'] = $this->config->item('base_url').'dashboard/';
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
		
		$arr = array();
		$postsArr = $this->postsmodel->getAllPosts(RECORDS_PER_PAGE, $offset, $loggeduser['id']);
		if(!empty($postsArr)) {
			foreach($postsArr as $val) {
				$arr[] = array(
								'id' => urlencode(parent::_fnEncrypt($val['id'], $this->qrEncryptionKey)),
								'title' => $val['title'],
								'description' => $val['description'],
								'createdByName' => $val['createdByName'],
								'date_created' => $val['date_created']
							);
			}
		}
		$data['posts'] = $arr;
		$this->load->library('pagination');
		$this->pagination->initialize($config);
		$data['pager'] = $this->pagination->create_links();
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/users/dashboard', $data);
		$this->load->view('front/base/footer', $footerData);
	}

	/**
	 * User Change Password
	**/
	public function changePassword()
	{
		$loggeduser = parent::getLoggedUser();
		$headerData = parent::getHeaderData();
		$footerData = parent::getFooterData();
		$data = array();
		
		$headerData['title'] = APP_NAME.'::Change Password';
		
		if($this->input->post('newPassword')){
			$oldPassword = $this->input->post('oldPassword', TRUE);
			$newPassword = $this->input->post('newPassword', TRUE);
			
			$userArr = $this->usersmodel->getUserById($loggeduser['id']);
			if(!empty($userArr)){
				if($userArr['password']==md5($oldPassword)){
					$this->db->where('id', $loggeduser['id']);
					$this->db->update('users', array('password'=>md5($newPassword)));
					
					$this->session->set_flashdata('notification', 'Password updated successfully.');
					header('location:'.$this->config->item('base_url').'changepassword'); exit;
				} else {
					$this->session->set_flashdata('notification', 'Invalid Old Password.');
					header('location:'.$this->config->item('base_url').'changepassword'); exit;
				}
			}
		}
		
		$this->load->view('front/base/header', $headerData);
		$this->load->view('front/users/changePassword', $data);
		$this->load->view('front/base/footer', $footerData);
	}
	
}

/* End of file UsersController.php */
/* Location: ./application/controllers/UsersController.php */