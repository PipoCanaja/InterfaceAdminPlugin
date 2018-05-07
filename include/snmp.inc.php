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

/* function snmp_set($device, $oid, $type, $value, $options = null, $mib = null, $mibdir = null)
 * 
 * Execute SNMP Set on device, using $config['InterfaceAdminPlugin_snmp_RW']
 */

function snmp_set($device, $oid, $type, $value, $options = null)
{
	global $config;
	$time_start = microtime(true);

	if (strstr($oid, ' ')) {
		//echo ("snmp_set on multiple OIDs not supported: $oid");
		return 'multiple-oid';
	}

	if (!isset($config['InterfaceAdminPlugin_snmp_RW']) || $config['InterfaceAdminPlugin_snmp_RW'] == '') {
		//echo ('No SNMP Write Community provided in config file ( $config["InterfaceAdminPlugin_snmp_RW"])');
		return 'community';
	}

	$community = $config['InterfaceAdminPlugin_snmp_RW'];
	$value=addslashes($value);
	$cmd= "snmpset -v2c $options -c $community $device $oid $type '".$value."'";
	$data = trim(external_exec($cmd), "\" \n\r");
	$res = $data;

	recordSnmpStatistic('snmpset', $time_start);
	if (preg_match('/(No Such Instance|No Such Object|No more variables left|Authentication failure)/i', $data)) {
		return false;
	} elseif ($data || $data === '0') {
		return $res;
	} else {
		return false;
	}
	return false;
}//end snmp_set()

?>
