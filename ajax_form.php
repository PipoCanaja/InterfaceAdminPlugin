<?php

/*
 * LibreNMS
 *
 * Copyright (c) 2018 PipoCanaja <https://github.com/PipoCanaja>
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the
 * Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.  Please see LICENSE.txt at the top level of
 * the source code distribution for details.
 */

use LibreNMS\Authentication\Auth;

$init_modules = array('web', 'auth', 'alerts');
require realpath(__DIR__ . '/../../..') . '/includes/init.php';
require realpath(__DIR__) . '/include/snmp.inc.php';

use phpseclib\Net\SSH2;


set_debug($_REQUEST['debug']);

/* 
 * Execute code to respond to interactive actions (button forms)
 */

if (!Auth::user()->hasGlobalAdmin()) {
	$response = array(
			'status'  => 'error',
			'message' => 'Need to be admin',
			);
	echo _json_encode($response);
	exit;
}

if (preg_match('/^[a-zA-Z0-9\-]+$/', $_POST['type']) == 1) {
	if (file_exists(realpath(__DIR__).'/forms/'.$_POST['type'].'.inc.php')) {
		include_once realpath(__DIR__).'/forms/'.$_POST['type'].'.inc.php';
	} else { 
		$response = array(
				'status'  => 'error',
				'message' => 'Function not found '.$_POST['type'],
				);
		echo _json_encode($response);
		exit;

		die ('not found '.$_POST['type']);
	}
}

