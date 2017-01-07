<?php

global $BootstrapMenus;
$BootstrapMenus = array(
	'main_menu' => array(
		'admin' => array(
			array('Admin' => array(
				'AdminUsers',
				'AdminPages',
				'SysInfo',
				'WikkaConfig'
			)),
			'[[CategoryCategory|Categories]]',
			'PageIndex',
			'RecentChanges',
			'RecentlyCommented',
			array('Profile' => array(
				'[[<<username>>|My User Page]]',
				'[[UserSettings|Settings]]',
				'<<logout>>',
			)),
		),
		'user' => array(
			'[[CategoryCategory|Categories]]',
			'PageIndex',
			'RecentChanges',
			'RecentlyCommented',
			array('Profile' => array(
				'[[<<username>>|My User Page]]',
				'[[UserSettings|Settings]]',
				'<<logout>>',
			)),
		),
		'default' => array(
			'[[CategoryCategory|Categories]]',
			'PageIndex',
			'RecentChanges',
			'RecentlyCommented',
			'[[UserSettings|Login/Register]]',
		)
	),

	'options_menu' => array(
		'admin' => array(
			'{{editlink}}',
			'{{revertlink}}',
			'{{deletelink}}',
			'{{clonelink}}',
			'{{historylink}}',
			'{{revisionlink}}',
			'{{ownerlink}}',
			'{{referrerslink}}'
		),
		'user' => array(
			'{{editlink}}',
			'{{clonelink}}',
			'{{historylink}}',
			'{{revisionlink}}',
			'{{ownerlink}}'
		),
		'default' => array(
			'{{editlink}}',
			'{{historylink}}',
			'{{revisionlink}}',
			'{{ownerlink}}',
			'Your hostname is {{whoami}}'
		),
	),
);
