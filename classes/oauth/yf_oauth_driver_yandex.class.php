<?php

load('oauth_driver', 'framework', 'classes/oauth/');
class yf_oauth_driver_yandex extends yf_oauth_driver {

	protected $url_authorize = 'https://oauth.yandex.ru/authorize';
	protected $url_access_token = 'https://oauth.yandex.ru/token';
	protected $url_user = 'https://login.yandex.ru/info';
	protected $scope = '';
	protected $get_access_token_method = 'POST';
	protected $url_params_access_token = array(
		'grant_type' => 'authorization_code',
	);

	/**
	*/
	function get_user_info() {
		$access_token = $this->_storage_get('access_token');
		if (!$access_token) {
			$access_token = $this->get_access_token();
			if (!$access_token) {
				$this->_storage_clean();
				js_redirect( $this->redirect_uri, $url_rewrite = false );
				return false;
			}
		}
		if (!$this->_storage_get('user')) {
			$url = $this->url_user.'?'.http_build_query(array(
				'oauth_token'	=> $access_token,
				'format'		=> 'json',
			));
			$result = common()->get_remote_page($url, $cache = false, $opts, $response);
			$result = $this->_decode_result($result, $response);
			if (isset($result['error']) || substr($response['http_code'], 0, 1) == '4') {
				$this->_storage_clean();
				js_redirect( $this->redirect_uri, $url_rewrite = false );
				return false;
			} else {
				$this->_storage_set('user_info_request', array('result' => $result, 'response' => $response));
				$this->_storage_set('user', $result);
			}
		}
		return $this->_storage_get('user');
	}
}