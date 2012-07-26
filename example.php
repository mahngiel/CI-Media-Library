<?php
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
 * Media Upload Preparation Example Controller
 *
 * Prepares and validates image files
 *
 * @package	CodeIgniter
 * @subpackage		CodeIgniter Controller
 * @category	Controller
 * @author		Mahngiel (a/k/a) Kris Reeck
 * @license		http://opensource.org/licenses/mit-license.php MIT License (MIT)
 * @filesource https://github.com/mahngiel/CI-Media-Library
 * @requires CodeIgniter v2.1 or greater
 */
 
class Example extends CI_Controller {
	
	/**
	 * Constructor
	 *
	 */	
	function __construct()
	{
		// Call the Controller constructor
		parent::__construct();	
		
		// Load Libraries
		$this->load->library('Media');
		
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Avatar
	 *
	 * Example usage for an avatar upload
	 * Expects an upload field with the name of 'avatar'
	 */
	 function avatar()
	 {
 		// Catch upload input file
		if( $file = $_FILES['avatar'] )
		{
			// Initialize file settings: @field name, @file rename, @max size
	    		$config = $this->media->config( 'avatar', strtolower( $file['name'] ), 1024 );
	    		
    			// Image Verification
    			if( $config['is_image'] )
    			{
    				// Verification passed, upload icon: @file data array, @upload dir, @width, @height, @keep
    				$upload = $this->media->upload_icon( $config, 'avatars', 100, 100, FALSE );
    			}
    			
    			// Image successfully uploaded, insert into users
			if( $upload )
			{
				// Prep data
				$update_array = array(
					'avatar'	=>	$config['file_name']
					);
					
				// Update user's avatar row
				$this->users->update_user( $user_id, $update_array );
			}
		}
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Banner
	 *
	 * Example usage for a banner upload
	 * Expects an upload field with the name of 'banner'
	 * Includes example supplementary data regarding the banner
	 */
	 function banner()
	 {
 		// Catch upload input file
		if( $file = $_FILES['banner'] )
		{
			// Initialize file settings: @field name, @file rename, @max size
	    		$config = $this->media->config( 'banner', str_replace(' ', '-', $file['name']), 1024 );
	    		
    			// Image Verification
    			if( $config['is_image'] )
    			{
    				// Verification passed, upload icon: @file data array, @upload dir, @width, @height
    				$upload = $this->media->upload_banner( $config, 'ad_banners', 960, 80 );
    			}
    			
    			// Image successfully uploaded, insert into rotating banner table
			if( $upload )
			{
				// Prep data
				$new_banner = array(
					'banner_img'	=>	$config['file_name'],
					'banner_alt'	=>	$this->input->post('banner_alt'),
					'banner_link'	=>	$this->input->post('banner_link'),
					);
				
				// Insert banner
				$this->media->insert('ad_banners', $new_banner);
			}
		}	
	}
	
	// ------------------------------------------------------------------------------------
	/**
	 * Gallery
	 *
	 * Example usage for a media gallery upload including thumbnail
	 * Expects an upload field with the name of 'gallery'
	 * Includes example supplementary data regarding the user uploading the image and some post data
	 */
	 function gallery()
	 {	 	
 		// Catch upload input file
		if( $file = $_FILES['image'] )
		{
			// Initialize file settings: @field name, @file rename, @max size
	    		$config = $this->media->config( 'image', strtolower(str_replace(' ', '-', $file['name'])), 4096 );
	    		
    			// Image Verification
    			if( $config['is_image'] )
    			{
    				// Verification passed, upload icon: @file data array, @upload dir, @width, @height, @keep
    				$thumb = $this->media->upload_icon( $config, 'gallery/thumbs', 80, 80, TRUE );
    				
    				// Verification passed, upload icon: @file data array, @upload dir
    				$upload = $this->media->upload_image( $config, 'gallery' );
    			}
    			
    			// Image successfully uploaded, insert into users
			if( $thumb && $upload )
			{
				// Prep data
				$insert_array = array(
					'image_name'		=>	$config['file_name'],
					'image_uploader'	=>	$user->user_id,
					'image_description'	=>	$this->input->post('description'),
					);
					
				// Insert image into database
				$this->gallery->insert_image( $insert_array );
			}
		}
	 }
	 
/* End of file example.php */
/* Location: ./application/controllers/example.php */