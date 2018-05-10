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

namespace LibreNMS\Plugins;

class InterfaceAdminPlugin
{
	public static function menu()
	{
		echo '<li><a href="plugin/p=InterfaceAdminPlugin">InterfaceAdminPlugin - Preferences</a></li>';
	}//end menu()


	/* public function device_overview_container($device_id)
	 *
	 * Display and execute code in the Device Page
	 */

	public function device_overview_container($device_id) {
		// No code here for this plugin
	}

	/* public function port_container($device, $port)
	 *
	 * Display and execute code in the Port Page
	 */

	public function port_container($device, $port) {
		echo ('<form class="form-horizontal" action="" method="post">');
		echo('<div class="container-fluid"><div class="row"> <div class="col-md-12"> <div class="panel panel-default panel-condensed"> <div class="panel-heading"><strong>Interface Enable / Disable via SNMP Write</strong> </div>');

		echo ('<div class="table-responsive"><table id="InterfaceAdminPlugin" class="table table-condensed bootgrid-table">');
		echo('<tbody><tr><td>
				<div><strong>Port Admin Status</strong> </div>
				<span><button type="submit" name="btn-interface_admin-shutdown" id="btn-interface_admin-shutdown" class="btn btn-danger">Disable port</button>
				</span><span>
				<button type="submit" name="btn-interface_admin-enable" id="btn-interface_admin-enable" class="btn btn-success">Enable port</button>
				</span>
				</tbody></td></tr>
				</div></div>
				');
		echo('</table></div></div></div></div></div></form>');
		echo ('<script>

				$("[name=\'btn-interface_admin-enable\']").on(\'click\', function(event) {
					event.preventDefault();
					var $this = $(this);
					var device_id = "'.$device['device_id'].'";
					var port_id = "'.$port['port_id'].'";
					var disp_toastr=toastr.info(\'Enabling '.$port['ifName'].'\',null,{timeOut:0, extendedTimeOut:0});
					$.ajax({
type: \'POST\',
url: \'plugins/InterfaceAdminPlugin/ajax_form.php\',
data: { type: "enable-port", port_id: port_id, device_id: device_id },
dataType: "html",
success: function(data){
var dataObject = jQuery.parseJSON(data);
toastr.clear(disp_toastr);
if (dataObject.status=="ok") { toastr.success(dataObject.message);} else { toastr.error(dataObject.message); }
},
error:function(){
toastr.error(\'Error AJAX\');
}          
});
					});



$("[name=\'btn-interface_admin-shutdown\']").on(\'click\', function(event) {
		event.preventDefault();
		var $this = $(this);
		var device_id = "'.$device['device_id'].'";
		var port_id = "'.$port['port_id'].'";
		var disp_toastr=toastr.info(\'Shutting down '.$port['ifName'].'\',null,{timeOut:0, extendedTimeOut:0});
		$.ajax({
type: \'POST\',
url: \'plugins/InterfaceAdminPlugin/ajax_form.php\',
data: { type: "shutdown-port", port_id: port_id, device_id: device_id },
dataType: "html",
success: function(data){
var dataObject = jQuery.parseJSON(data);
toastr.clear(disp_toastr);
if (dataObject.status=="ok") { toastr.success(dataObject.message);} else { toastr.error(dataObject.message); }
},
error:function(){
toastr.error(\'Error AJAX\');
}
});
		});
</script>
');
}
}
