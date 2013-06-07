<?php
require_once('openid_common.php');

if( empty($openid_error) )
{
	$response = $consumer->complete($return_to);
	// ��������� �����
	if ($response->status == Auth_OpenID_CANCEL) {
		// ��� ��������, ��� �������������� ���� ��������
		$openid_error = '����������� ��������.';
	} else if ($response->status == Auth_OpenID_FAILURE) {
		// �������. ���������� ���������
		$openid_error = "����������� �� �������: " . $response->message;
	} else if ($response->status == Auth_OpenID_SUCCESS) {
		// ��� � �������. �������� identify url � ������ ������������
		
		$openid = $response->getDisplayIdentifier();
		 
		if( !login_by_openid($openid) )
		{
			//�������� ������ � ������������
			$sreg_resp = Auth_OpenID_SRegResponse::fromSuccessResponse($response);

			$sreg = $sreg_resp->contents();

			print_r($sreg);
	
			$email = @$sreg['email'];
			$nickname = @$sreg['nickname'];
			$fullname = @$sreg['fullname'];
			$gender = @$sreg['gender'];
			
			if( empty($nickname) || check_user_name($nickname) )
			{
				//���� �� �� ����� ��������� ������������ � ��������� ����� ��� ���� ������ �� ��������� ���, ���������� ��� ����
				$nickname = str_replace('.','-',preg_replace('#[^a-zA-Z.\-_]#','',str_replace('http://','',$openid)));
				if( check_user_name($nickname) )
					$nickname = null; //���� ���� ��������������� ��� �� �������� (��� ��� �����) - �� ��� ���� ���-�� ������. � ���� ��������� ������������ �� ������� ���� ������.
			}

			if( !create_user($nickname,$fullname,$email,$gender) )
			{
				$openid_error = '������ �������� ������������';
			} else
			{
				if( !link_openid_user($user_id,$openid) )
					$openid_error = '������ ���������� OpenID � �������������';
				else
				{
					login_by_openid($openid);
					echo '������� ������������ �� OpenID � ������������<br/>';
					exit;
				}
			}
		} else
		{
			echo '������������ �� OpenID<br/>';
			exit;
		}
	}
}

echo $openid_error;

?>