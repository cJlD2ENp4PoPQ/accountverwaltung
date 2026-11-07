<?php
//in env.inc.php umbenennen.
//Defaults sind hier definiert. ENV_*-Variablen aus docker/.web.env
//(docker-compose.yaml: env_file) überschreiben sie; leerer Wert = Default greift.

if (!function_exists('env_cfg')) {
	function env_cfg(string $envName, $default) {
		$value = getenv($envName);
		return ($value === false || $value === '') ? $default : $value;
	}
}

//Admin-E-Mail-Adresse
$GLOBALS['env_admin_email']=env_cfg('ENV_ADMIN_EMAIL','');

//internes Supportsystem aktivieren?
$GLOBALS['env_enable_support_page']=(int)env_cfg('ENV_ENABLE_SUPPORT_PAGE','1');

//DE-KB-DB aktivieren?
$GLOBALS['env_enable_de_kb_db']=(int)env_cfg('ENV_ENABLE_DE_KB_DB','1');

//Forenmenüpunkt aktivieren?
$GLOBALS['env_enable_forum_connect']=(int)env_cfg('ENV_ENABLE_FORUM_CONNECT','0');

//URL-Liste
$GLOBALS['env_url_portal']=env_cfg('ENV_URL_PORTAL','');
$GLOBALS['env_url_impressum']=env_cfg('ENV_URL_IMPRESSUM','');
$GLOBALS['env_url_datenschutz']=env_cfg('ENV_URL_DATENSCHUTZ','');
$GLOBALS['env_url_agb']=env_cfg('ENV_URL_AGB','');

//Accountverwaltung
$GLOBALS['env_db_loginsystem_host']=env_cfg('ENV_DB_LOGINSYSTEM_HOST','');
$GLOBALS['env_db_loginsystem_user']=env_cfg('ENV_DB_LOGINSYSTEM_USER','');
$GLOBALS['env_db_loginsystem_password']=env_cfg('ENV_DB_LOGINSYSTEM_PASSWORD','');
$GLOBALS['env_db_loginsystem_database']=env_cfg('ENV_DB_LOGINSYSTEM_DATABASE','');

//Logging-DB
$GLOBALS['env_db_logging_host']=env_cfg('ENV_DB_LOGGING_HOST','');
$GLOBALS['env_db_logging_user']=env_cfg('ENV_DB_LOGGING_USER','');
$GLOBALS['env_db_logging_password']=env_cfg('ENV_DB_LOGGING_PASSWORD','');
$GLOBALS['env_db_logging_database']=env_cfg('ENV_DB_LOGGING_DATABASE','');

//SMTP-Postfach
$GLOBALS['env_mail_server']=env_cfg('ENV_MAIL_SERVER','');
$GLOBALS['env_mail_noreply']=env_cfg('ENV_MAIL_NOREPLY','');
$GLOBALS['env_mail_user']=env_cfg('ENV_MAIL_USER','');
$GLOBALS['env_mail_password']=env_cfg('ENV_MAIL_PASSWORD','');

//RPC Authcode
$GLOBALS['env_rpc_authcode']=env_cfg('ENV_RPC_AUTHCODE','');

//Datenbankverbindung für den Spielserver, erreichbar per databaseKey
$GLOBALS['env_databaseKey']['xde']['host']=env_cfg('ENV_DATABASEKEY_XDE_HOST','');
$GLOBALS['env_databaseKey']['xde']['database']=env_cfg('ENV_DATABASEKEY_XDE_DATABASE','');
$GLOBALS['env_databaseKey']['xde']['user']=env_cfg('ENV_DATABASEKEY_XDE_USER','');
$GLOBALS['env_databaseKey']['xde']['password']=env_cfg('ENV_DATABASEKEY_XDE_PASSWORD','');

$GLOBALS['env_databaseKey']['sde']['host']=env_cfg('ENV_DATABASEKEY_SDE_HOST','');
$GLOBALS['env_databaseKey']['sde']['database']=env_cfg('ENV_DATABASEKEY_SDE_DATABASE','');
$GLOBALS['env_databaseKey']['sde']['user']=env_cfg('ENV_DATABASEKEY_SDE_USER','');
$GLOBALS['env_databaseKey']['sde']['password']=env_cfg('ENV_DATABASEKEY_SDE_PASSWORD','');

?>
