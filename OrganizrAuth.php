<?php
# Copyright (c) MantisBT Team - mantisbt-dev@lists.sourceforge.net
# Copyright (c) 2021 Mat Cox - Mat@tmmn.uk
# Licensed under the MIT license

/**
 * Organizr Auth
 */
class OrganizrAuthPlugin extends MantisPlugin  {
	/**
	 * A method that populates the plugin information and minimum requirements.
	 * @return void
	 */
	function register() {
		$this->name = plugin_lang_get( 'title' );
		$this->description = plugin_lang_get( 'description' );
		$this->page = 'config_page';

		$this->version = '1.0';
		$this->requires = array(
			'MantisCore' => '2.5.0',
		);

		$this->author = 'Mat Cox';
		$this->contact = 'Mat@tmmn.uk';
		$this->url = 'https://tmmn.uk';
	}

	/**
	 * plugin hooks
	 * @return array
	 */
	function hooks() {
		$t_hooks = array(
			'EVENT_CORE_READY' => 'auto_login',
		);

		return $t_hooks;
	}

	function auto_login() {
		if ( auth_is_user_authenticated() ) {
			return;
		}
		
		### ORGANIZR CONFIG ###
		$Organizr_Cookie = plugin_config_get('organizr_cookie')	; // Organizr Token cookie name
		$Organizr_URL = plugin_config_get('organizr_url'); // The URL of Organizr
		### ORGANIZR CONFIG ###

		################################## Organizr ##################################
		# Set Authentication as Organizr if defined.
		if (isset($_COOKIE[$Organizr_Cookie]))
		{
			$post = [
				'Token' => $_COOKIE[$Organizr_Cookie],
			];

			$ch = curl_init($Organizr_URL . "/api/v2/token/validate");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
			$response = curl_exec($ch);
			curl_close($ch);
			$response = json_decode($response);
						
			$t_remote_user = $response->response->data->username;
			$t_remote_email = $response->response->data->email;

			## Remove if published
			if ($response->response->data->groupID <= "0") {
				$t_remote_access_level = "90";
			} elseif ($response->response->data->groupID <= "1") {
				$t_remote_access_level = "70";
			} elseif  ($response->response->data->groupID <= "15") {
				$t_remote_access_level = "25";
			} else {
				$t_remote_access_level = "10";
			}
			## Remove if published			
		
			$t_username = $t_remote_user;
			$t_user_id = empty($t_username) ? false : user_get_id_by_name( $t_username );
			if ( !$t_user_id ) {
				if (!empty($t_username)) {
					$t_email = $response->response->data->email;
					$t_realname = $response->response->data->username;
					$t_user_id = user_create($t_username, auth_generate_random_password(), $t_email, plugin_config_get('organizr_access_level'), false, true, $t_username );
				}
			}
			## Remove if published
			else {
				$p_user_fields['access_level'] = $t_remote_access_level;
				$t_user_access_level = user_set_fields( $t_user_id, $p_user_fields);
				if( !$t_user_access_level ) {
					trigger_error( "Could not update remote user." );
				}
			}
			## Remove if published
			auth_login_user( $t_user_id );
		} else {
			return;
		}
	}
}
