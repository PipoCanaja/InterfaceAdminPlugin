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


include "../../../includes/snmp.inc.php";

/* function snmp_set($device, $oid, $type, $value, $options = null, $mib = null, $mibdir = null)
 * 
 * Execute SNMP Set on device, using default LibreNMS community settings for current device
 */

function snmp_set($device, $oid, $type, $value, $options = null)
{
	global $config;
	$time_start = microtime(true);

	if (strstr($oid, ' ')) {
		//echo ("snmp_set on multiple OIDs not supported: $oid");
		return 'multiple-oid';
	}

	$snmpAuth = snmp_gen_auth($device);
	$value=addslashes($value);
	$cmd= "snmpset $snmpAuth $options ".' '.$device[transport].':'.$device[hostname].':'.$device[port]." $oid $type '".$value."'";
	$data = trim(external_exec($cmd), "\" \n\r");
	$res = $data;

	recordSnmpStatistic('snmpset', $time_start);
	if (preg_match('/(No Such Instance|No Such Object|No more variables left|Authentication failure)/i', $data)) {
		return false;
	} elseif (preg_match('/(No Access)/i', $data)) {
                return 'community';
         } elseif ($data || $data === '0') {
		return $data;
	} elseif ($data =='') {
                return 'community';
        } else {
		return false; //$cmd." :". $data.":";
	}
	return $data;
}//end snmp_set()

?>
