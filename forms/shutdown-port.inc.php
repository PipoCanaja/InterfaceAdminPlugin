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

header('Content-type: application/json');

if (!Auth::user()->hasGlobalAdmin()) {
	$response = array(
			'status'  => 'error',
			'message' => 'Need to be admin',
			);
	echo _json_encode($response);
	exit;
}



/* 
 * Default answer for AJAX call
 * 
 */
$status  = 'error';
$message = 'Error disabling port';

/* 
 * Retrieving device and port parameters
 * 
 */
$device_id = mres($_POST['device_id']);
$port_id = mres($_POST['port_id']);

/* 
 * Quick sanity checks
 * 
 */
if (!is_numeric($device_id)) {
	$message = 'Missing device id';
} elseif (!is_numeric($port_id)) {
	$message = 'Missing port id';
} else {

        /* 
         * Get device/port objects
         * 
         */
        $port   = cleanPort(get_port_by_id($port_id));
        $device = device_by_id_cache($device_id);

        /* 
         * Let's do the SNMP Set to the device
         * 
         */
	$res = snmp_set($device, ".1.3.6.1.2.1.2.2.1.7.$port[ifIndex]", 'i', 2);


        /* 
         * Return the results of the call
         * 
         */
	if ($res == false) {
		$message = 'Could not disable '.$port['ifName'].' port';
	} elseif ($res == 'community') {
                $message = 'Community in LibreNMS is probably ReadOnly. No answer from device with current community';
                $status = 'error';

        } elseif ($res == 'multiple-oid') { 
                $message = 'Cannot send multiple OIDs to snmp_set, plugin internal error, please report';
                $status = 'error';
 
        } else {
		$message = 'Port '.$port['ifName'].' disabled successfully. ';
		$status = 'ok';
		log_event('GUI : Disabled Port', $device_id, 'interface', 3, $port_id);
	} 
}

$response = array(
		'status'        => $status,
		'message'       => $message,
		'extra'         => $res,
		);
echo _json_encode($response);

?>
