<?php
//Liest ENV_*-Variablen (z. B. aus docker/.web.env via docker-compose env_file).
//Leerer oder nicht gesetzter Wert = $default wird verwendet.
if (!function_exists('env_cfg')) {
	function env_cfg(string $envName, $default) {
		$value = getenv($envName);
		return ($value === false || $value === '') ? $default : $value;
	}
}
