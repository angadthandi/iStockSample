<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Author : Angad Thandi
 * Base controller is used to define common methods that are required throughout the application
**/

class BaseController extends CI_Controller {

	/**
	 * constructor
	**/
	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->qrEncryptionKey = "29xdViKrAnjI2SL2"; /*secret key to encrypt*/
		//$this->output->enable_profiler(TRUE);
	}
	
	/**
	 * Determine if the current user is logged in, if so return the loggeduser, otherwise redirect to login page.
	 */
	protected function getLoggedUser()
	{
		$loggeduser = unserialize($this->session->userdata('loggeduser'));
		$this->load->helper('url');
		if (($loggeduser == NULL) || ($_SERVER["SERVER_PORT"] == 443)) {
			redirect($this->config->item('base_url').'login');
		} 
		return $loggeduser;
	}
	
	/**
	 * get header data
	**/
	public function getHeaderData()
	{
		$headerArr = array('title'=>APP_NAME);
		return $headerArr;
	}
	
	/**
	 * get footer data
	**/
	public function getFooterData()
	{
		$footerArr = array();
		return $footerArr;
	}
	
	public function pageNotFound()
	{
		$this->load->view('pageNotFound');
	}
	
	
	/************************************
	 *
	 * AES ENCRYPT/DECRYPT
	 *
	************************************/
	
	/**
	 * AES 128 Encryption
	**/
	protected function _fnEncrypt($sValue, $sSecretKey)
	{
		return rtrim(
			$this->_base64urlEncode(
				mcrypt_encrypt(
					MCRYPT_RIJNDAEL_128,
					$sSecretKey, $sValue, 
					MCRYPT_MODE_ECB, 
					mcrypt_create_iv(
						mcrypt_get_iv_size(
							MCRYPT_RIJNDAEL_128, 
							MCRYPT_MODE_ECB
						), 
					MCRYPT_RAND)
				)
			), "\0"
		);
	}
	
	/**
	 * AES 128 Decryption
	**/
	protected function _fnDecrypt($sValue, $sSecretKey)
	{
		return rtrim(
			mcrypt_decrypt(
				MCRYPT_RIJNDAEL_128, 
				$sSecretKey, 
				$this->_base64urlDecode($sValue), 
				MCRYPT_MODE_ECB,
				mcrypt_create_iv(
					mcrypt_get_iv_size(
						MCRYPT_RIJNDAEL_128,
						MCRYPT_MODE_ECB
					), 
					MCRYPT_RAND
				)
			), "\0"
		);
	}
	
	/**
	 * replace chars while encoding
	**/
	protected function _base64urlEncode($s) {
		return str_replace(array('+', '/'), array('-', '_'), base64_encode($s));
	}

	/**
	 * replace chars while decoding
	**/
	protected function _base64urlDecode($s) {
		return base64_decode(str_replace(array('-', '_'), array('+', '/'), $s));
	}
}

/* End of file BaseController.php */
/* Location: ./application/controllers/BaseController.php */