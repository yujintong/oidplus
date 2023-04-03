<?php

/*
 * OIDplus 2.0
 * Copyright 2019 - 2023 Daniel Marschall, ViaThinkSoft
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

use ViaThinkSoft\OIDplus\OIDplus;
use ViaThinkSoft\OIDplus\OIDplusException;

/**
 * @param string $privKey
 * @return bool
 */
function is_privatekey_encrypted(string $privKey): bool {
	return strpos($privKey,'BEGIN ENCRYPTED PRIVATE KEY') !== false;
}

/**
 * @param string $privKey
 * @param string $pubKey
 * @return bool
 */
function verify_private_public_key(string $privKey, string $pubKey): bool {
	if (!function_exists('openssl_public_encrypt')) return false;
	try {
		if (empty($privKey)) return false;
		if (empty($pubKey)) return false;
		$data = generateRandomString(25);
		$encrypted = '';
		$decrypted = '';
		if (!@openssl_public_encrypt($data, $encrypted, $pubKey)) return false;
		if (!@openssl_private_decrypt($encrypted, $decrypted, $privKey)) return false;
		return $decrypted == $data;
	} catch (\Exception $e) {
		return false;
	}
}

/**
 * @param string $privKeyOld
 * @param string|null $passphrase_old
 * @param string|null $passphrase_new
 * @return false|string
 */
function change_private_key_passphrase(string $privKeyOld, string $passphrase_old=null, string $passphrase_new=null) {
	$pkey_config = array(
	    //"digest_alg" => "sha512",
	    //"private_key_bits" => 2048,
	    //"private_key_type" => OPENSSL_KEYTYPE_RSA,
	    "config" => class_exists("\\ViaThinkSoft\\OIDplus\\OIDplus") ? OIDplus::getOpenSslCnf() : @getenv('OPENSSL_CONF')
	);
	$privKeyNew = @openssl_pkey_get_private($privKeyOld, $passphrase_old);
	if ($privKeyNew === false) return false;
	if (!@openssl_pkey_export($privKeyNew, $privKeyNewExport, $passphrase_new, $pkey_config)) return false;
	if ($privKeyNewExport === "") return false;
	return "$privKeyNewExport";
}

/**
 * @param string $privKey
 * @param string $passphrase
 * @return false|string
 */
function decrypt_private_key(string $privKey, string $passphrase) {
	return change_private_key_passphrase($privKey, $passphrase, null);
}

/**
 * @param string $privKey
 * @param string $passphrase
 * @return false|string
 */
function encrypt_private_key(string $privKey, string $passphrase) {
	return change_private_key_passphrase($privKey, null, $passphrase);
}

/**
 * @param string $data
 * @return int
 */
function smallhash(string $data): int { // get 31 bits from SHA1. Values 0..2147483647
	return (hexdec(substr(sha1($data),-4*2)) & 0x7FFFFFFF);
}

/**
 * @param string $name
 * @return array
 */
function split_firstname_lastname(string $name): array {
	$ary = explode(' ', $name);
	$last_name = array_pop($ary);
	$first_name = implode(' ', $ary);
	return array($first_name, $last_name);
}

/**
 * @return void
 */
function originHeaders() {
	// CORS
	// Author: Till Wehowski
	// TODO: add to class OIDplus

	header("Access-Control-Allow-Credentials: true");
	header("Access-Control-Allow-Origin: ".strip_tags(((isset($_SERVER['HTTP_ORIGIN'])) ? $_SERVER['HTTP_ORIGIN'] : "*")));

	header("Access-Control-Allow-Headers: If-None-Match, X-Requested-With, Origin, X-Frdlweb-Bugs, Etag, X-Forgery-Protection-Token, X-CSRF-Token");

	if (isset($_SERVER['HTTP_ORIGIN'])) {
		header('X-Frame-Options: ALLOW-FROM '.$_SERVER['HTTP_ORIGIN']);
	} else {
		header_remove("X-Frame-Options");
	}

	$expose = array('Etag', 'X-CSRF-Token');
	foreach (headers_list() as $num => $header) {
		$h = explode(':', $header);
		$expose[] = trim($h[0]);
	}
	header("Access-Control-Expose-Headers: ".implode(',',$expose));

	header("Vary: Origin");
}

if (!function_exists('mb_wordwrap')) {
	/**
	 * @param string $str
	 * @param int $width
	 * @param string $break
	 * @param bool $cut
	 * @return string
	 */
	function mb_wordwrap(string $str, int $width = 75, string $break = "\n", bool $cut = false): string {
		// https://stackoverflow.com/a/4988494/488539
		$lines = explode($break, $str);
		foreach ($lines as &$line) {
			$line = rtrim($line);
			if (mb_strlen($line) <= $width) {
				continue;
			}
			$words = explode(' ', $line);
			$line = '';
			$actual = '';
			foreach ($words as $word) {
				if (mb_strlen($actual.$word) <= $width) {
					$actual .= $word.' ';
				} else {
					if ($actual != '') {
						$line .= rtrim($actual).$break;
					}
					$actual = $word;
					if ($cut) {
						while (mb_strlen($actual) > $width) {
							$line .= mb_substr($actual, 0, $width).$break;
							$actual = mb_substr($actual, $width);
						}
					}
					$actual .= ' ';
				}
			}
			$line .= trim($actual);
		}
		return implode($break, $lines);
	}
}

/**
 * @param string $out
 * @param string $contentType
 * @param string $filename
 * @return void
 */
function httpOutWithETag(string $out, string $contentType, string $filename='') {
	$etag = md5($out);
	header("Etag: $etag");
	header("Content-MD5: $etag"); // RFC 2616 clause 14.15
	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && (trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag)) {
		if (PHP_SAPI != 'cli') @http_response_code(304); // 304 Not Modified
	} else {
		header("Content-Type: $contentType");
		if (!empty($filename)) {
			header('Content-Disposition:inline; filename="'.$filename.'"');
		}
		echo $out;
	}
	die();
}

/**
 * @param string $str
 * @param array $args
 * @return string
 */
function my_vsprintf(string $str, array $args): string {
	$n = 1;
	foreach ($args as $val) {
		$str = str_replace("%$n", $val, $str);
		$n++;
	}
	return str_replace("%%", "%", $str);
}

/**
 * @param string $str
 * @param mixed ...$sprintfArgs
 * @return string
 * @throws \ViaThinkSoft\OIDplus\OIDplusConfigInitializationException
 * @throws \ViaThinkSoft\OIDplus\OIDplusException
 */
function _L(string $str, ...$sprintfArgs): string {
	static $translation_array = array();
	static $translation_loaded = null;

	$str = trim($str);

	if (!class_exists(OIDplus::class)) {
		return my_vsprintf($str, $sprintfArgs);
	}

	$lang = OIDplus::getCurrentLang();
	$ta = OIDplus::getTranslationArray($lang);
	$res = (isset($ta[$lang]) && isset($ta[$lang][$str])) ? $ta[$lang][$str] : $str;

	$res = str_replace('###', OIDplus::baseConfig()->getValue('TABLENAME_PREFIX', ''), $res);

	return my_vsprintf($res, $sprintfArgs);
}

/**
 * @param array $params
 * @param string $key
 * @return void
 * @throws \ViaThinkSoft\OIDplus\OIDplusConfigInitializationException
 * @throws \ViaThinkSoft\OIDplus\OIDplusException
 */
function _CheckParamExists(array $params, string $key) {
	if (class_exists(OIDplusException::class)) {
		if (!isset($params[$key])) throw new OIDplusException(_L('Parameter %1 is missing', $key));
	} else {
		if (!isset($params[$key])) throw new Exception(_L('Parameter %1 is missing', $key));
	}
}

/**
 * @param string $cont
 * @return array
 */
function extractHtmlContents(string $cont): array {
	// make sure the program works even if the user provided HTML is not UTF-8
	$cont = convert_to_utf8_no_bom($cont);

	$out_js = '';
	$m = array();
	preg_match_all('@<script[^>]*>(.+)</script>@ismU', $cont, $m);
	foreach ($m[1] as $x) {
		$out_js = $x . "\n\n";
	}

	$out_css = '';
	$m = array();
	preg_match_all('@<style[^>]*>(.+)</style>@ismU', $cont, $m);
	foreach ($m[1] as $x) {
		$out_css = $x . "\n\n";
	}

	$out_html = $cont;
	$out_html = preg_replace('@^(.+)<body[^>]*>@isU', '', $out_html);
	$out_html = preg_replace('@</body>.+$@isU', '', $out_html);
	$out_html = preg_replace('@<title>.+</title>@isU', '', $out_html);
	$out_html = preg_replace('@<h1>.+</h1>@isU', '', $out_html, 1);
	$out_html = preg_replace('@<script[^>]*>(.+)</script>@ismU', '', $out_html);
	$out_html = preg_replace('@<style[^>]*>(.+)</style>@ismU', '', $out_html);

	return array($out_html, $out_js, $out_css);
}

/**
 * @param string $password
 * @param bool $raw_output
 * @return string
 * @throws Exception
 */
function sha3_512(string $password, bool $raw_output=false): string {
	if (hash_supported_natively('sha3-512')) {
		return hash('sha3-512', $password, $raw_output);
	} else {
		return \bb\Sha3\Sha3::hash($password, 512, $raw_output);
	}
}

/**
 * @param string $message
 * @param string $key
 * @param bool $raw_output
 * @return string
 */
function sha3_512_hmac(string $message, string $key, bool $raw_output=false): string {
	// RFC 2104 HMAC
	if (hash_hmac_supported_natively('sha3-512')) {
		return hash_hmac('sha3-512', $message, $key, $raw_output);
	} else {
		return \bb\Sha3\Sha3::hash_hmac($message, $key, 512, $raw_output);
	}
}

/**
 * @param string $password
 * @param string $salt
 * @param int $iterations
 * @param int $length
 * @param bool $binary
 * @return string
 */
function sha3_512_pbkdf2(string $password, string $salt, int $iterations, int $length=0, bool $binary=false): string {
	if (hash_pbkdf2_supported_natively('sha3-512')) {
		return hash_pbkdf2('sha3-512', $password, $salt, $iterations, $length, $binary);
	} else {
		return \bb\Sha3\Sha3::hash_pbkdf2($password, $salt, $iterations, 512, $length, $binary);
	}
}

/**
 * @return bool
 */
function url_post_contents_available(): bool {
	return function_exists('curl_init');
}

/**
 * @param string $url
 * @param array $params
 * @param array $extraHeaders
 * @param string $userAgent
 * @return string|false
 * @throws \ViaThinkSoft\OIDplus\OIDplusException
 */
function url_post_contents(string $url, array $params=array(), array $extraHeaders=array(), string $userAgent='ViaThinkSoft-OIDplus/2.0') {
	$postFields = http_build_query($params);

	$headers = array(
		"User-Agent: $userAgent",
		"Content-Length: ".strlen($postFields)
	);

	foreach ($extraHeaders as $name => $val) {
		$headers[] = "$name: $val";
	}

	if (function_exists('curl_init')) {
		$ch = curl_init();
		if (class_exists(OIDplus::class)) {
			if (ini_get('curl.cainfo') == '') curl_setopt($ch, CURLOPT_CAINFO, OIDplus::localpath() . 'vendor/cacert.pem');
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		$res = @curl_exec($ch);
		$error_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		@curl_close($ch);
		if ($error_code >= 400) return false;
		if ($res === false) return false;
	} else {
		throw new OIDplusException(_L('The "%1" PHP extension is not installed at your system. Please enable the PHP extension <code>%2</code>.','CURL','php_curl'));
	}
	return $res;
}

/**
 * @param string $url
 * @param array $extraHeaders
 * @param string $userAgent
 * @return string|false
 */
function url_get_contents(string $url, array $extraHeaders=array(), string $userAgent='ViaThinkSoft-OIDplus/2.0') {
	$headers = array("User-Agent: $userAgent");
	foreach ($extraHeaders as $name => $val) {
		$headers[] = "$name: $val";
	}
	if (function_exists('curl_init')) {
		$ch = curl_init();
		if (class_exists(OIDplus::class)) {
			if (ini_get('curl.cainfo') == '') curl_setopt($ch, CURLOPT_CAINFO, OIDplus::localpath() . 'vendor/cacert.pem');
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_AUTOREFERER, true);
		$res = @curl_exec($ch);
		$error_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE);
		@curl_close($ch);
		if ($error_code >= 400) return false;
		if ($res === false) return false;
	} else {
		// Attention: HTTPS only works if OpenSSL extension is enabled.
		// Our supplement does not help...
		$opts = [
			"http" => [
				"method" => "GET",
				"header" => implode("\r\n",$headers)."\r\n"
			]
		];
		$context = stream_context_create($opts);
		$res = @file_get_contents($url, false, $context);
		if ($res === false) return false;
	}
	return $res;
}

/**
 * @return string
 */
function getSortedQuery(): string {
	// https://stackoverflow.com/a/51777249/488539
	$url = [];
	parse_str($_SERVER['QUERY_STRING'], $url);
	ksort($url);
	return http_build_query($url);
}

/**
* @param array &$rows
* @param string $fieldName
* @return void
*/
function natsort_field(&$rows, string $fieldName) {
	usort($rows, function($a,$b) use($fieldName) {
		if ($a[$fieldName] == $b[$fieldName]) return 0; // equal
		$ary = array(
			-1 => $a[$fieldName],
			1 => $b[$fieldName]
		);
		natsort($ary);
		$keys = array_keys($ary);
		return $keys[0];
	});
}

/**
 * @param array $ary
 * @return \stdClass
 */
function array_to_stdobj(array $ary): \stdClass {
	$obj = new \stdClass;
	foreach ($ary as $name => $val) {
		$obj->$name = $val;
	}
	return $obj;
}

/**
 * @param \stdClass $obj
 * @return array
 */
function stdobj_to_array(\stdClass $obj): array {
	$ary = array();
	foreach ($obj as $name => $val) {
		$ary[$name] = $val;
	}
	return $ary;
}
