<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------
/**
 * Media Upload Preparation Library
 *
 * Prepares and validates image files
 *
 * @package	CodeIgniter
 * @subpackage		CodeIgniter Media Library
 * @category	Library
 * @author		Mahngiel (a/k/a) Kris Reeck
 * @license		http://opensource.org/licenses/mit-license.php MIT License (MIT)
 * @filesource https://github.com/mahngiel/CI-Media-Library
 */

class Media {

	var $CI;
	
	function __construct()
	{	
		// Create an instance to CI
		$this->CI =& get_instance();
	}

	// ------------------------------------------------------------------------------------
	/**
	 * Config
	 *
	 * Uploads an image to the default path and applies CI's upload library constraints
	 *
	 * @access	private
	 * @param	string // name of the upload field
	 * @param	string // name file is to be renamed to
	 * @param integer // maximum file size
	 * @return	array // contains all image information
	 */
	function config( $field, $name, $maxsize = 1024 )
	{
		// Image restraints
		$file_config = array(
				'allowed_types'	=>	'jpg|jpeg|png|gif',
				'upload_path'	=>	UPLOAD,
				'max_size'		=>	$maxsize,
				'remove_spaces'	=>	TRUE,
				'overwrite'		=>	TRUE,
				'file_name'	=>	$name,
			);

		//Load the library with restraints and run method, passing the meta data to data array	
		$this->CI->load->library('upload', $file_config);
		
		// File is not permissible
		if(!$this->CI->upload->do_upload($field) )
		{
			// Config failed, issue error flashdata
			$this->CI->session->set_flashdata('error', $this->CI->upload->display_errors());
			return FALSE;
		}
		else
		{
			return $image_data = $this->CI->upload->data();
		}
		
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Upload Icon
	 *
	 * Template for images which have limited dimension irregardless of aspect ratio 
	 *
	 * @access	private
	 * @param array // accepts image information
	 * @param	string // the path the file will be moved to
	 * @param	integer // defined image width
	 * @param integer // defined image height
	 * @param boolean // whether or not to delete the original image
	 * @return	boolean  
	 */
	function upload_icon( $image_data = array(), $path = '', $w = 64, $h = 64, $keep_image = FALSE )
	{
		// Check for valid data
		if( empty( $image_data ) OR !is_array( $image_data ) )
		{
			// Invalid data, return FALSE
			return FALSE;
		}
		
		$data = array(
			'source_image' 		=> 	$image_data['full_path'],
			'new_image' 		=> 	UPLOAD . $path,
			'maintain_ratio' 	=> 	FALSE,
			'width'			=>	$w,
			'height'			=>	$h,
			);
		
		// Load image library and resize
		$this->CI->load->library('image_lib', $data);
		$this->CI->image_lib->resize();
		
		//Delete original image?
		if( !(bool)$keep_image )
			unlink(UPLOAD . $image_data['file_name']);

		return TRUE;
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Upload Banner
	 *
	 * Template for images which have limited dimension with regard for aspect ratio 
	 *
	 * @access	private
	 * @param array // accepts image information
	 * @param	string // the path the file will be moved to
	 * @param	integer // defined image width
	 * @param integer // defined image height
	 * @return	boolean  
	 */
	function upload_banner( $image_data = array(), $path = '', $w = 710, $h = 95 ) 
	{
		// Check for valid data
		if(empty($image_data) OR !is_associative($image_data))
		{
			// Invalid data, return FALSE
			return FALSE;
		}
		
		$data = array(
			'source_image' 		=> 	$image_data['full_path'],
			'new_image' 		=> 	UPLOAD . $path,
			'maintain_ratio' 	=> 	TRUE,
			'width'			=>	$w,
			'height'			=>	$h,
			'master_dim'		=> 	'width'
			);
		
		// Load image library and resize
		$this->CI->load->library('image_lib', $data);
		$this->CI->image_lib->resize();
		
		//delete original image
		unlink(UPLOAD . $image_data['file_name']);
					
		return TRUE;
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Upload Image
	 *
	 * Template for images which have no constraints.  Useful for galleries 
	 *
	 * @access	private
	 * @param array // accepts image information
	 * @param	string // the path the file will be moved to
	 * @return	boolean  
	 */
	function upload_image($image_data = array(), $path = '') 
	{
		// Check for valid data
		if(empty($image_data) OR !is_associative($image_data))
		{
			// Invalid data, return FALSE
			return FALSE;
		}
		$data = array(
			'source_image' 		=> 	$image_data['full_path'],
			'new_image' 		=> 	UPLOAD . $path,
			'quality'			=>	'100%'
		);
		
		// Load image library and resize
		$this->CI->load->library('image_lib', $data);
		$this->CI->image_lib->resize();
		
		//delete original image
		unlink(UPLOAD . $image_data['file_name']);
					
		return TRUE;
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Insert Image
	 *
	 * Inserts the image into a databse table
	 *
	 * @access	private
	 * @param	string // the table to save the data
	 * @param array // the insert values
	 * @return	integer // database insert id  
	 */
	function insert($table, $data = array())
	{
		// Check to see if we have valid data
		if(empty($data) OR !is_array($data))
		{
			// Data is invalid, return FALSE
			return FALSE;
		}
		
		// Data is valid, insert the data in the database
		$this->CI->db->insert($table, $data);
		
		return $entry_id = $this->CI->db->insert_id();
	}
	
}

/* End of file media.php */
/* Location: ./application/libraries/media.php */
