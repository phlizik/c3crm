<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 ********************************************************************************/

require_once('include/CRMSmarty.php');
require_once('modules/Import/ImportAccount.php');
require_once('include/utils/CommonUtils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $current_user;

global $import_mod_strings;

$focus = 0;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";


$log->info($mod_strings['LBL_MODULE_NAME'] . " Upload Step 1");

$smarty = new CRMSmarty();





$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMP", $import_mod_strings);

$smarty->assign("CATEGORY", $_REQUEST['parenttab']);

$import_object_array = Array(
				"Accounts"=>"ImportAccount",
				"Products"=>"ImportProduct",
				"Notes"=>"ImportNote"
			    );

if(isset($_REQUEST['module']) && $_REQUEST['module'] != '')
{
	$module = "Accounts";
	$object_name = $import_object_array[$module];
	//support customized module
	$callInitImport = false;
	
	if($object_name == null) {
		require_once("modules/$module/$module.php");
		$object_name = $module;
		$callInitImport = true;
	}
	$focus = new $object_name();
	if($callInitImport) $focus->initImport();
}
else
{
	echo "Sorry! Import Option is not provided for this module.";
	exit;
}

if(isset($_REQUEST['return_module'])) $smarty->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $smarty->assign("RETURN_ACTION", $_REQUEST['return_action']);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);
//$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);

$smarty->assign("HEADER", $app_strings['LBL_IMPORT']." ". $mod_strings['LBL_MODULE_NAME']);
$smarty->assign("HAS_HEADER_CHECKED"," CHECKED");

//$smarty->assign("OVERWRITE_CHECKED"," CHECKED");

$smarty->assign("MODULE", "Accounts");
$smarty->assign("SOURCE", $_REQUEST['source']);

//we have set this as default. upto 4.2.3 we have Outlook, Act, SF formats. but now CUSTOM is enough to import
$lang_key = "CUSTOM";
$smarty->assign("INSTRUCTIONS_TITLE",$mod_strings["LBL_IMPORT_{$lang_key}_TITLE"]);

for($i = 1; isset($mod_strings["LBL_{$lang_key}_NUM_$i"]);$i++)
{
	$smarty->assign("STEP_NUM",$mod_strings["LBL_NUM_$i"]);
	$smarty->assign("INSTRUCTION_STEP",$mod_strings["LBL_{$lang_key}_NUM_$i"]);
}

$smarty->display("ImportStepXin1.tpl");

?>
