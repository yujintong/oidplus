<?php

/*
 * OIDplus 2.0
 * Copyright 2019 Daniel Marschall, ViaThinkSoft
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

define('IN_OIDPLUS', true);

// Before we do ANYTHING, check for dependencies! Do not include anything (except the GMP supplement) yet.

if (version_compare(PHP_VERSION, '7.0.0') < 0) {
	// Reasons why we currently require PHP 7.0:
	// - Return values (e.g. "function foo(): array") (added 2020-04-06 at the database classes)
	//   Note: By removing these return values (e.g. removing ": array"), you *might* be
	//   able to run OIDplus with PHP lower than version 7.0 (not tested)
	//
	// Reasons why we would require PHP 7.1 in the future (*):
	// - Nullable return values (e.g. "function foo(): ?array")
	//
	// (*) Currently we do NOT require 7.1, because some distros are still using PHP 7.0 (e.g. Debian 9 which has LTS support till May 2022).
	echo '<h1>OIDplus error</h1>';
	echo "<p>OIDplus requires at least PHP version 7.0! You are currently using version " . PHP_VERSION . "</p>\n";
	die();
}

include_once __DIR__ . '/gmp_supplement.inc.php';

$missing_dependencies = array();

if (!function_exists('gmp_init')) {
	// GMP Required for includes/uuid_functions.inc.php
	//                  includes/ipv6_functions.inc.php
	//                  plugins/adminPages/400_oidinfo_export/oidinfo_api.inc.php (if GMP is not available, BC will be used)
	// Note that gmp_supplement.inc.php will implement the GMP functions if BCMath is present.
	// This is the reason why we use function_exists('gmp_init') instead of extension_loaded('gmp')
        $missing_dependencies[] = 'GMP (Install it using <code>sudo aptitude update && sudo aptitude install php-gmp && sudo service apache2 restart</code> on Linux systems.)' .
	                          '<br>or alternatively<br>' .
	                          'BCMath (Install it using <code>sudo aptitude update && sudo aptitude install php-bcmath && sudo service apache2 restart</code> on Linux systems.)';
}

if (!function_exists('mb_substr')) {
	// Required for includes/classes/OIDplusSessionHandler.class.php
	//              includes/oid_utils.inc.php
	//              3p/minify/path-converter/Converter.php
	//              3p/0xbb/Sha3.class.php
	$missing_dependencies[] = 'MBString (Install it using <code>sudo aptitude update && sudo aptitude install php-mbstring && sudo service apache2 restart</code> on Linux systems.)';
}

if (count($missing_dependencies) >= 1) {
	echo '<h1>OIDplus error</h1>';
	echo '<p>The following PHP extensions need to be installed in order to run OIDplus.</p>';
	echo '<ul>';
	foreach ($missing_dependencies as $dependency) {
		echo '<li>'.$dependency.'</li>';
	}
	echo '</ul>';
	die();
}

unset($missing_dependencies);

// Now we can continue!

if (php_sapi_name() != 'cli') {
	header('X-Content-Type-Options: nosniff');
	header('X-XSS-Protection: 1; mode=block');
	header("Content-Security-Policy: default-src 'self' blob: https://fonts.gstatic.com https://www.google.com/ https://www.gstatic.com/ https://cdnjs.cloudflare.com/; ".
	       "style-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com/; ".
	       "img-src data: http: https:; ".
	       "script-src 'self' 'unsafe-inline' 'unsafe-eval' blob: https://www.google.com/ https://www.gstatic.com/ https://cdnjs.cloudflare.com/ https://polyfill.io/; ".
	       "frame-ancestors 'none'; ".
	       "object-src 'none'");
	header('X-Frame-Options: SAMEORIGIN');
	header('Referrer-Policy: no-referrer-when-downgrade');
}

require_once __DIR__ . '/../3p/0xbb/Sha3.class.php';

require_once __DIR__ . '/functions.inc.php';
require_once __DIR__ . '/oid_utils.inc.php';
require_once __DIR__ . '/uuid_utils.inc.php';
require_once __DIR__ . '/ipv4_functions.inc.php';
require_once __DIR__ . '/ipv6_functions.inc.php';
require_once __DIR__ . '/anti_xss.inc.php';
require_once __DIR__ . '/vnag_framework.inc.php';

// ---

spl_autoload_register(function ($class_name) {
	$candidate = __DIR__ . '/classes/' . $class_name . '.class.php';
	if (file_exists($candidate)) require_once $candidate;
});
