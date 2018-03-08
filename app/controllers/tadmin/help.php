<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
class Help extends Controller
{
	function __construct()
	{
		parent::__construct();
		check_is_login();
	}
	function index()
	{
		
	}
	
	function echo_phpinfo()
	{
		echo phpinfo();
	}
}
?>