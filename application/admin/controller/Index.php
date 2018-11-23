<?php
namespace app\admin\controller;

/**
* 
*/
class Index extends Base
{
	
	public function index()
	{
    //die('index');
		return $this->fetch();
	}

  public function test()
  {
    return 'test-page';
  }
}