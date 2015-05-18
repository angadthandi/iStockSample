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
					$commentHtml = '<div class="col-sm-12 commentClass" id="commentDiv_'.$commentArr["id"].'">';
					$commentHtml .= '<a href="javascript:void(0);" class="list-group-item staticAreaClass" id="commentHtml_'.$commentArr["id"].'">';
					$commentHtml .= '<div class="hover-btn">
									<button title="Delete Comment" type="button" class="close deleteCommentClass" data-dismiss="alert" id="deleteComment_'.$commentArr["id"].'">
									<span aria-hidden="true">×</span>
									<span class="sr-only">Close</span>
									</button>
									</div>';
					$commentHtml .= '<p class="list-group-item-text" id="commentDescription_'.$commentArr["id"].'">'.wordwrap($commentArr['description'], 8, "\n", true).'</p>';
					$commentHtml .= '<p class="list-group-item-text">Commented By : '.$commentArr['createdByName'].'</p>';
					$commentHtml .= '<p class="list-group-item-text">Date : '.$commentArr['date_created'].'</p>';
					$commentHtml .= '<button title="Edit Comment" type="button" class="btn btn-default btn-sm editCommentClass" id="editComment_'.$commentArr["id"].'">';
					$commentHtml .= '<span class="glyphicon glyphicon-edit"></span> Edit Comment';
					$commentHtml .= '</button>';
					$commentHtml .= '</a>';
					$commentHtml .= '<a href="javascript:void(0);" class="list-group-item hide dynamicAreaClass" id="editCommentText_'.$commentArr["id"].'">';
					$commentHtml .= '<textarea class="form-control" id="commentText_'.$commentArr["id"].'">'.$commentArr['description'].'</textarea>';
					$commentHtml .= '<button type="button" class="btn btn-default btn-sm saveCommentClass" id="editCommentBtn_'.$commentArr["id"].'">Save</button>';
					$commentHtml .= '<button type="button" class="btn btn-default btn-sm cancelCommentClass">Cancel</button>';
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
    
    /**
	 * This function is meant to edit a comment.
	**/
	public function editComment()
	{
		$status = -1;
		$updatedComment = '';
		$loggeduser = NULL;
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		if(!empty($loggeduser)){
			$commentId = $this->input->post('commentId',TRUE);
			$commentVal = $this->input->post('commentVal',TRUE);
			if(!empty($commentId) && !empty($commentVal)) {
				// check if user has rights to delete a comment, i.e. the post or comment has been posted by the user
				$commentArr = $this->commentsmodel->getCommentById($commentId);
				if(!empty($commentArr) && ($loggeduser['id']==$commentArr['commentedById'] || $loggeduser['id']==$commentArr['postCreatedById'])) {
					$this->db->where('id', $commentId);
					$this->db->update('comments', array('description'=>$commentVal));
					$updatedComment = wordwrap($commentVal, 8, "\n", true);
					$status = 1;
				}
			}
		}
		echo json_encode(array('status'=>$status, 'updatedComment'=>$updatedComment)); exit;
	}
	
}

/* End of file CommentsController.php */
/* Location: ./application/controllers/CommentsController.php */