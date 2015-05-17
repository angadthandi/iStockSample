<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author : Angad Thandi
 * comments controller is used to define methods that provide comments related functionality
**/

require_once 'BaseController.php';

class CommentsController extends BaseController {

	/**
	 * constructor
	**/
	public function __construct()
	{
		parent::__construct();
		$this->load->model('commentsmodel');
	}
    
    /**
	 * This function is meant for creating new comments.
	**/
	public function createNewComment()
	{
		$status = -1;
		$commentHtml = '';
		$loggeduser = NULL;
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		if(!empty($loggeduser)){
			$postedComment = $this->input->post('postedComment',TRUE);
			$encPostId = $this->input->post('postId',TRUE);
			if(!empty($postedComment) && !empty($encPostId)) {
				$postId = parent::_fnDecrypt(urldecode($encPostId), $this->qrEncryptionKey);
				$data=array(
						'user_id'=>$loggeduser['id'],
						'post_id'=>$postId,
						'description'=>$postedComment,
						'date_created'=>date('Y-m-d H:i:s')
					);
				$this->db->insert('comments',$data);
				$commentId = $this->db->insert_id();
				
				$commentArr = $this->commentsmodel->getCommentById($commentId);
				if(!empty($commentArr)){
					$commentHtml = '<div class="col-sm-12" id="commentDiv_'.$commentArr["id"].'">';
					$commentHtml .= '<a href="javascript:void(0);" class="list-group-item">';
					$commentHtml .= '<div class="hover-btn">
									<button type="button" class="close deleteCommentClass" data-dismiss="alert" id="deleteComment_'.$commentArr["id"].'">
									<span aria-hidden="true">×</span>
									<span class="sr-only">Close</span>
									</button>
									</div>';
					$commentHtml .= '<p class="list-group-item-text">'.$commentArr['description'].'</p>';
					$commentHtml .= '<p class="list-group-item-text">Commented By : '.$commentArr['createdByName'].'</p>';
					$commentHtml .= '<p class="list-group-item-text">Date : '.$commentArr['date_created'].'</p>';
					$commentHtml .= '</a>';
					$commentHtml .= '</div>';
				}
				$status = 1;
			}
		}
		echo json_encode(array('status'=>$status, 'commentHtml'=>$commentHtml)); exit;
	}
    
    /**
	 * This function is meant for deleting a comment.
	**/
	public function deleteComment()
	{
		$status = -1;
		$loggeduser = NULL;
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		if(!empty($loggeduser)){
			$commentId = $this->input->post('commentId',TRUE);
			if(!empty($commentId)) {
				// check if user has rights to delete a comment, i.e. the post or comment has been posted by the user
				$commentArr = $this->commentsmodel->getCommentById($commentId);
				if(!empty($commentArr) && ($loggeduser['id']==$commentArr['commentedById'] || $loggeduser['id']==$commentArr['postCreatedById'])) {
					$this->db->where('id', $commentId);
					$this->db->delete('comments');
					$status = 1;
				}
			}
		}
		echo json_encode(array('status'=>$status)); exit;
	}
	
}

/* End of file CommentsController.php */
/* Location: ./application/controllers/CommentsController.php */