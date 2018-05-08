<?php

class MenuTreeTest extends WebTestCase
{
	public $fixtures=array(
		'menuTrees'=>'MenuTree',
	);

	public function testShow()
	{
		$this->open('?r=menuTree/view&id=1');
	}

	public function testCreate()
	{
		$this->open('?r=menuTree/create');
	}

	public function testUpdate()
	{
		$this->open('?r=menuTree/update&id=1');
	}

	public function testDelete()
	{
		$this->open('?r=menuTree/view&id=1');
	}

	public function testList()
	{
		$this->open('?r=menuTree/index');
	}

	public function testAdmin()
	{
		$this->open('?r=menuTree/admin');
	}
}
