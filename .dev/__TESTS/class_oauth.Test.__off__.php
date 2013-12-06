<?php

require dirname(__FILE__).'/yf_unit_tests_setup.php';

class class_oauth_test extends PHPUnit_Framework_TestCase {
	public function test_01() {
		$data = array(
			'oauth_version' => '2.0',
			'dialog_url' => 'https://github.com/login/oauth/authorize?client_id={CLIENT_ID}&redirect_uri={REDIRECT_URI}&scope={SCOPE}&state={STATE}',
			'access_token_url' => 'https://github.com/login/oauth/access_token',
			'user_info_url' => 'https://api.github.com/user',
			'dev_register_url' => '',
		);
		$json = '
{
  "href": {
    "keys": "https://github.com/settings/applications/new",
    "docs": "http://developer.github.com/v3/",
    "apps": "https://github.com/settings/applications",
    "provider": "https://github.com/"
  },
  "api_url": "https://api.github.com/",
  "url": "https://github.com/login/oauth",
  "oauth2": {
    "authorize": "/authorize",
    "access_token": "/access_token",
    "request": {
      "query": {
        "access_token": "{{token}}"
      }
    },
    "refresh": {
      "url": "/token",
      "method": "post",
      "query": {
        "refresh_token":"{{refresh_token}}",
        "client_id":"{client_id}",
        "client_secret":"{client_secret}",
        "grant_type":"refresh_token"
      }
    },
    "parameters": {
      "client_secret": "string",
      "client_id": "string",
      "scope": {
        "values": {
          "public_repo": "Read/write access to public repos and organizations.",
          "gist": "Write access to gists.",
          "notifications": "Read access to a user\u2019s notifications. repo is accepted too.",
          "user:email": "Read access to a user\u2019s email addresses.",
          "repo:status": "Read/write access to public and private repository commit statuses. This scope is only necessary to grant other users or services access to private repository commit statuses without granting access to the code. The repo and public_repo scopes already include access to commit status for private and public repositories respectively.",
          "repo": "Read/write access to public and private repos and organizations.",
          "user:follow": "Access to follow or unfollow other users.",
          "delete_repo": "Delete access to adminable repositories.",
          "user": "Read/write access to profile info only. Note: this scope includes user:email and user:follow."
        },
        "cardinality": "*",
        "separator": " ",
        "type": "string"
      }
    }
  },
  "name": "github"
}';
		$this->assertEquals($data, _class('oauth')->_parse_provider_config_json($json) );
	}

}