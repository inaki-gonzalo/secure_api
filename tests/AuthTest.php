<?php
declare(strict_types=1);
require '../src/Auth.php';
use PHPUnit\Framework\TestCase;

final class AuthTest extends TestCase
{

	
    public function testRegularAuthenticationSucceeds()
    {
		$config=parse_ini_file("../private/credentials.ini");
		$user_id=$config['valid_user'];
		$user_key=$config['valid_key'];
		$auth=new Auth();
		$result=$auth->authenticate_regular_user($user_id, $user_key);
		$this->assertEquals( $result, TRUE);
    }
    
     public function testRegularInvalidUser()
    {
		$config=parse_ini_file("../private/credentials.ini");
		$user_id=$config['invalid_user'];
		$user_key=$config['valid_key'];
		$auth=new Auth();
		$result=$auth->authenticate_regular_user($user_id, $user_key);
		$this->assertEquals( $result, FALSE);
    }
    
    public function testAdminAuthenticationSucceeds()
    {
		$config=parse_ini_file("../private/credentials.ini");
		$user_id=$config['valid_user'];
		$user_key=$config['valid_key'];
		$auth=new Auth();
		$result=$auth->authenticate_admin_user($user_id, $user_key);
		$this->assertEquals( $result, TRUE);
    }
    
     public function testAdminInvalidUser()
    {
		$config=parse_ini_file("../private/credentials.ini");
		$user_id=$config['invalid_user'];
		$user_key=$config['valid_key'];
		$auth=new Auth();
		$result=$auth->authenticate_regular_user($user_id, $user_key);
		$this->assertEquals( $result, FALSE);
    }
}
