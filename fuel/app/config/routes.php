<?php
return array(
	'_root_'  => 'home/index',  // The default route
	'_404_'   => 'welcome/404',    // The main 404 route
	'transactions' => 'transactions/index',
	'transactions/create' => 'transactions/create',
	'transactions/edit/(:num)' => 'transactions/edit/$1',
	'transactions/update/(:num)' => 'transactions/update/$1',
	'transactions/delete/(:num)' => 'transactions/delete/$1',

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),
);
