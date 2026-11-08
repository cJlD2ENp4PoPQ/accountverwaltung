<?php
require_once __DIR__ . '/env_cfg.inc.php';

$GLOBALS['sv_debugmode']=(int)env_cfg('ENV_SV_DEBUGMODE','1');

//id für das pcs
$sv_pcs_id=(int)env_cfg('ENV_SV_PCS_ID','99');

//id für das logging
$sv_servid=(int)env_cfg('ENV_SV_SERVID','99');

//servertag für die accountverwaltung
$sv_server_tag=env_cfg('ENV_SV_SERVER_TAG','AID');
?>
