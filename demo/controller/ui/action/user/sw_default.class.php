<?php
namespace ui\user;
use lib\controller\sw_action;

class sw_default extends sw_action
{
	public function action_default()
	{
		$this->json_stdout(array('hello swanphp'));	
	}	
}
