<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

class Hop_mailinglist_at_login_ext {

    var $name       	= 'Hop mailing list at login';
    var $version        = '1.0';
    var $description    = 'Subscribe to a mailing list when logging in';
    var $settings_exist = 'n';
    var $docs_url       = ''; // 'https://ellislab.com/expressionengine/user-guide/';

    var $settings       = array();

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    function __construct($settings = '')
    {
        $this->settings = $settings;
    }
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see https://ellislab.com/codeigniter/user-guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	function activate_extension()
	{
		$this->settings = array(
			//'max_link_length'   => 18,
			//'truncate_cp_links' => 'no',
			//'use_in_forum'      => 'no'
		);


		$data = array(
			'class'     => __CLASS__,
			'method'    => 'subscribe_to',
			'hook'      => 'member_member_login_single',
			//'settings'  => serialize($this->settings),
			'settings'	=> serialize($this->settings),
			'priority'  => 10,
			'version'   => $this->version,
			'enabled'   => 'y'
		);

		ee()->db->insert('extensions', $data);
	}
	
	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return  mixed   void on update / false if none
	 */
	function update_extension($current = '')
	{
		if ($current == '' OR $current == $this->version)
		{
			return FALSE;
		}

		if ($current < '1.0')
		{
			// Update to version 1.0
		}

		ee()->db->where('class', __CLASS__);
		ee()->db->update(
					'extensions',
					array('version' => $this->version)
		);
	}
	
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
		ee()->db->where('class', __CLASS__);
		ee()->db->delete('extensions');
	}

	/**
	 * Meat and potatoe of the extension
	 * 
	 */
	function subscribe_to($hook_data)
	{
		//print_r($hook_data);
		$mailing_list_id = ee()->input->post('mailing_list');
		//email is the ee one so it's supposed to be OK (there is one or there is not)
		$email = $hook_data->email;
		if ($email == "")return; //member has no email
		
		if ($mailing_list_id == "" || !ctype_digit($mailing_list_id) )return;
		
		$query_email = ee()->db->select('email')
			->from('mailing_list')
			->where('email', $email)
			->get();
		
		if ($query_email->num_rows() == 0)
		{
			$data = array(
				'list_id'		=> $mailing_list_id,
				'authcode'		=> random_string('alnum', 10),
				'email'			=> $email,
				'ip_address'	=> ee()->input->ip_address()
			);
			
			ee()->db->insert('mailing_list', $data);
		}
		else
		{
			
		}
		
		return;
	}
}