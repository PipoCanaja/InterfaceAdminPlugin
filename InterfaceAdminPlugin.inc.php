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


/*
 * Code displayed when loading the plugin
 */

if ($config['InterfaceAdminPlugin_snmp_RW'] != '') {
	echo ('The InterfaceAdminPlugin is up and running, and RW community is defined in the config.php');
} else {
	echo ('In order to use this Plugin, please define your device SNMP Write community in the config.php file, using the following code : </br> $config["InterfaceAdminPlugin_snmp_RW"] = "MyLittleRWCommunity";');
}

?>
