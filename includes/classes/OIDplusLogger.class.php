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

namespace ViaThinkSoft\OIDplus;

// phpcs:disable PSR1.Files.SideEffects
\defined('INSIDE_OIDPLUS') or die;
// phpcs:enable PSR1.Files.SideEffects

class OIDplusLogger extends OIDplusBaseClass {

	/**
	 * This function splits a mask code containing multiple components
	 * (delimited by '+' or '/') in single components
	 * It takes care that '+' and '/' inside brackets won't be used to split the codes
	 * Also, brackets can be escaped.
	 * The severity block (optional, must be standing in front of a component)
	 * is handled too. Inside the severity block, you may only use '/' to split components.
	 * The severity block will be implicitly repeated from the previous components if a component
	 * does not feature one.
	 *
	 * "[ERR]AAA(BBB)+CCC(DDD)"   ==> array(
	 *                                 array(array("ERR"),"AAA(BBB)"),
	 *                                 array(array("ERR"),"CCC(DDD)")
	 *                              )
	 * "[INFO]AAA(B+BB)+[WARN]CCC(DDD)"  ==> array(
	 *                                 array(array("INFO"),"AAA(B+BB)"),
	 *                                 array(array("WARN"),"CCC(DDD)")
	 *                              )
	 * "[?WARN/!OK] AAA(B\)BB)+CCC(DDD)" ==> array(
	 *                                 array(array("?WARN", "!OK"),"AAA(B\)BB)"),
	 *                                 array(array("?WARN", "!OK"),"CCC(DDD)")
	 *                              )
	 * @param string $maskcodes
	 * @return array|false
	 */
	private function split_maskcodes(string $maskcodes) {
		$out = array();
		$sevs = array(); // Note: The severity block will repeat for the next components if not changed explicitly

		$code = '';
		$sev = '';
		$bracket_level = 0;
		$is_escaping = false;
		$inside_severity_block = false;
		for ($i=0; $i<strlen($maskcodes); $i++) {
			$char = $maskcodes[$i];

			if ($inside_severity_block) {
				// Severity block (optional)
				// e.g.  [?WARN/!OK] ==> $sevs = array("?WARN", "!OK")
				if ($char == '\\') {
					if ($is_escaping) {
						$is_escaping = false;
						$sev .= $char;
					} else {
						$is_escaping = true;
					}
				}
				else if ($char == '[') {
					if ($is_escaping) {
						$is_escaping = false;
					} else {
						$bracket_level++;
					}
					$sev .= $char;
				}
				else if ($char == ']') {
					if ($is_escaping) {
						$is_escaping = false;
						$sev .= $char;
					} else {
						$bracket_level--;
						if ($bracket_level < 0) return false;
						if ($bracket_level == 0) {
							$inside_severity_block = false;
							if ($sev != '') $sevs[] = $sev;
							$sev = '';
						} else {
							$sev .= $char;
						}
					}
				}
				else if ((($char == '/')) && ($bracket_level == 1)) {
					if ($is_escaping) {
						$is_escaping = false;
						$sev .= $char;
					} else {
						if ($sev != '') $sevs[] = $sev;
						$sev = '';
					}
				} else {
					if ($is_escaping) {
						// This would actually be an error, because we cannot escape this
						$is_escaping = false;
						$sev .= '\\' . $char;
					} else {
						$sev .= $char;
					}
				}
			} else {
				// Normal data (after the severity block)
				if (($char == '[') && ($code == '')) {
					$inside_severity_block = true;
					$bracket_level++;
					$sevs = array();
				}
				else if ($char == '\\') {
					if ($is_escaping) {
						$is_escaping = false;
						$code .= $char;
					} else {
						$is_escaping = true;
					}
				}
				else if ($char == '(') {
					if ($is_escaping) {
						$is_escaping = false;
					} else {
						$bracket_level++;
					}
					$code .= $char;
				}
				else if ($char == ')') {
					if ($is_escaping) {
						$is_escaping = false;
					} else {
						$bracket_level--;
						if ($bracket_level < 0) return false;
					}
					$code .= $char;
				}
				else if ((($char == '+') || ($char == '/')) && ($bracket_level == 0)) {
					if ($is_escaping) {
						$is_escaping = false;
						$code .= $char;
					} else {
						if ($code != '') $out[] = array($sevs,$code);
						$code = '';
					}
				} else {
					if ($is_escaping) {
						// This would actually be an error, because we cannot escape this
						$is_escaping = false;
						$code .= '\\' . $char;
					} else {
						$code .= $char;
					}
				}
			}
		}
		if ($code != '') $out[] = array($sevs,$code);
		if ($inside_severity_block) return false;

		return $out;
	}

	private $missing_plugin_queue = array();

	/**
	 * @return bool
	 * @throws OIDplusException
	 */
	public function reLogMissing(): bool {
		while (count($this->missing_plugin_queue) > 0) {
			$item = $this->missing_plugin_queue[0];
			if (!$this->log_internal($item[0], $item[1], false)) return false;
			array_shift($this->missing_plugin_queue);
		}
		return true;
	}

	/**
	 * @param string $maskcodes A description of the mask-codes can be found in OIDplusLogger.class.php
	 * @param string $message The message of the event
	 * @param mixed ...$sprintfArgs If used, %1..%n in $maskcodes and $message will be replaced, like _L() does.
	 * @return bool
	 * @throws OIDplusException
	 */
	public function log(string $maskcodes, string $message, ...$sprintfArgs): bool {
		$this->reLogMissing(); // try to re-log failed requests

		$maskcodes = my_vsprintf($maskcodes, $sprintfArgs);
		$message = my_vsprintf($message, $sprintfArgs);

		if (strpos($maskcodes,'%') !== false) {
			throw new OIDplusException(_L('Unresolved wildcards in logging maskcode'));
		}

		return $this->log_internal($maskcodes, $message, true);
	}

	/**
	 * @param string $maskcodes
	 * @param string $message
	 * @param bool $allow_delayed_log
	 * @return bool
	 * @throws OIDplusException
	 */
	private function log_internal(string $maskcodes, string $message, bool $allow_delayed_log): bool {
		$loggerPlugins = OIDplus::getLoggerPlugins();
		if (count($loggerPlugins) == 0) {
			// The plugin might not be initialized in OIDplus::init()
			// yet. Remember the log entries for later submission during
			// OIDplus::init();
			if ($allow_delayed_log) $this->missing_plugin_queue[] = array($maskcodes, $message);
			return false;
		}

		// What is a mask code?
		// A mask code gives information about the log event:
		// 1. The severity (info, warning, error)
		// 2. In which logbook(s) the event shall be placed
		// Example:
		// The event would be:
		// "Person 'X' moves from house 'A' to house 'B'"
		// This event would affect the person X and the two houses,
		// so, instead of logging into 3 logbooks separately,
		// you would create a mask code that tells the system
		// to put the message into the logbooks of person X,
		// house A, and house B.

		$logEvent = new OIDplusLogEvent($message);

		// A mask code with multiple components is split into single codes
		// using '+' or '/', e.g. "OID(x)+RA(x)" would be split to "OID(x)" and "RA(x)"
		// which would result in the message being placed in the logbook of OID x,
		// and the logbook of the RA owning OID x.
		$maskcodes_ary = $this->split_maskcodes($maskcodes);
		if ($maskcodes_ary === false) {
			throw new OIDplusException(_L('Invalid maskcode "%1" (failed to split)',$maskcodes));
		}
		foreach ($maskcodes_ary as list($sevs,$maskcode)) {
			// At the beginning of each mask code, you can define a severity.
			// If you have a mask code with multiple components, you don't have to place the
			// severity for each component. You can just leave it at the beginning.
			// e.g. "[WARN]OID(x)+RA(x)" is equal to "[WARN]OID(x)+[WARN]RA(x)"
			// You can also put different severities for the components:
			// e.g. "[INFO]OID(x)+[WARN]RA(x)" would be a info for the OID, but a warning for the RA.
			// If you want to make the severity dependent on wheather the user is logged in or not,
			// prepend "?" or "!" and use '/' as delimiter
			// Example: "[?WARN/!OK]RA(x)" means: If RA is not logged in, it is a warning; if it is logged in, it is an success
			$severity = 0; // default severity = none
			$severity_online = 0;
			foreach ($sevs as $sev) {
				switch (strtoupper($sev)) {
					// [OK]   = Success
					//          Numeric value: 1
					//          Rule of thumb: YOU have done something and it was successful
					case '?OK':
						$severity_online = 1;
						break;
					case '!OK':
					case  'OK':
						$severity = 1;
						break;
					// [INFO] = Informational
					//          Numeric value: 2
					//          Rule of thumb: Someone else has done something (that affects you) and it was successful
					case '?INFO':
						$severity_online = 2;
						break;
					case '!INFO':
					case  'INFO':
						$severity = 2;
						break;
					// [WARN] = Warning
					//          Numeric value: 3
					//          Rule of thumb: Something happened (probably someone did something) and it affects you
					case '?WARN':
						$severity_online = 3;
						break;
					case '!WARN':
					case  'WARN':
						$severity = 3;
						break;
					// [ERR]  = Error
					//          Numeric value: 4
					//          Rule of thumb: Something failed (probably someone did something) and it affects you
					case '?ERR':
						$severity_online = 4;
						break;
					case '!ERR':
					case  'ERR':
						$severity = 4;
						break;
					// [CRIT] = Critical
					//          Numeric value: 5
					//          Rule of thumb: Something happened (probably someone did something) which is not an error,
					//          but some critical situation (e.g. hardware failure), and it affects you
					case '?CRIT':
						$severity_online = 5;
						break;
					case '!CRIT':
					case  'CRIT':
						$severity = 5;
						break;
					default:
						throw new OIDplusException(_L('Invalid maskcode "%1" (Unknown severity "%2")',$maskcodes,$sev));
				}
			}

			// OID(x)	Save log entry into the logbook of: Object "x"
			$m = array();
			if (preg_match('@^OID\((.+)\)$@ismU', $maskcode, $m)) {
				$object_id = $m[1];
				$logEvent->addTarget(new OIDplusLogTargetObject($severity, $object_id));
				if ($object_id == '') throw new OIDplusException(_L('OID logger mask requires OID'));
			}

			// SUPOID(x)	Save log entry into the logbook of: Parent of object "x"
			else if (preg_match('@^SUPOID\((.+)\)$@ismU', $maskcode, $m)) {
				$object_id         = $m[1];
				if ($object_id == '') throw new OIDplusException(_L('SUPOID logger mask requires OID'));
				$obj = OIDplusObject::parse($object_id);
				if ($obj) {
					if ($objParent = $obj->getParent()) {
						$parent = $objParent->nodeId();
						$logEvent->addTarget(new OIDplusLogTargetObject($severity, $parent));
					} else {
						//throw new OIDplusException(_L('%1 has no parent',$object_id));
					}
				} else {
					throw new OIDplusException(_L('SUPOID logger mask: Invalid object %1',$object_id));
				}
			}

			// OIDRA(x)?	Save log entry into the logbook of: Logged in RA of object "x"
			// Remove or replace "?" by "!" if the entity does not need to be logged in
			else if (preg_match('@^OIDRA\((.+)\)([\?\!])$@ismU', $maskcode, $m)) {
				$object_id         = $m[1];
				$ra_need_login     = $m[2] == '?';
				if ($object_id == '') throw new OIDplusException(_L('OIDRA logger mask requires OID'));
				$obj = OIDplusObject::parse($object_id);
				if ($obj) {
					if ($ra_need_login) {
						foreach (OIDplus::authUtils()->loggedInRaList() as $ra) {
							if ($obj->userHasWriteRights($ra)) $logEvent->addTarget(new OIDplusLogTargetUser($severity_online, $ra->raEmail()));
						}
					} else {
						// $logEvent->addTarget(new OIDplusLogTargetUser($severity, $obj->getRa()->raEmail()));
						foreach (OIDplusRA::getAllRAs() as $ra) {
							if ($obj->userHasWriteRights($ra)) $logEvent->addTarget(new OIDplusLogTargetUser($severity, $ra->raEmail()));
						}
					}
				} else {
					throw new OIDplusException(_L('OIDRA logger mask: Invalid object "%1"',$object_id));
				}
			}

			// SUPOIDRA(x)?	Save log entry into the logbook of: Logged in RA that owns the superior object of "x"
			// Remove or replace "?" by "!" if the entity does not need to be logged in
			else if (preg_match('@^SUPOIDRA\((.+)\)([\?\!])$@ismU', $maskcode, $m)) {
				$object_id         = $m[1];
				$ra_need_login     = $m[2] == '?';
				if ($object_id == '') throw new OIDplusException(_L('SUPOIDRA logger mask requires OID'));
				$obj = OIDplusObject::parse($object_id);
				if ($obj) {
					if ($ra_need_login) {
						foreach (OIDplus::authUtils()->loggedInRaList() as $ra) {
							if ($obj->userHasParentalWriteRights($ra)) $logEvent->addTarget(new OIDplusLogTargetUser($severity_online, $ra->raEmail()));
						}
					} else {
						if ($objParent = $obj->getParent()) {
							// $logEvent->addTarget(new OIDplusLogTargetUser($severity, $objParent->getRa()->raEmail()));
							foreach (OIDplusRA::getAllRAs() as $ra) {
								if ($obj->userHasParentalWriteRights($ra)) $logEvent->addTarget(new OIDplusLogTargetUser($severity, $ra->raEmail()));
							}
						} else {
							//throw new OIDplusException(_L('%1 has no parent, therefore also no parent RA',$object_id));
						}
					}
				} else {
					throw new OIDplusException(_L('SUPOIDRA logger mask: Invalid object "%1"',$object_id));
				}
			}

			// RA(x)?	Save log entry into the logbook of: Logged in RA "x"
			// Remove or replace "?" by "!" if the entity does not need to be logged in
			else if (preg_match('@^RA\((.*)\)([\?\!])$@ismU', $maskcode, $m)) {
				$ra_email          = $m[1];
				$ra_need_login     = $m[2] == '?';
				if (!empty($ra_email)) {
					if ($ra_need_login && OIDplus::authUtils()->isRaLoggedIn($ra_email)) {
						$logEvent->addTarget(new OIDplusLogTargetUser($severity_online, $ra_email));
					} else if (!$ra_need_login) {
						$logEvent->addTarget(new OIDplusLogTargetUser($severity, $ra_email));
					}
				}
			}

			// A?	Save log entry into the logbook of: A logged in admin
			// Remove or replace "?" by "!" if the entity does not need to be logged in
			else if (preg_match('@^A([\?\!])$@imU', $maskcode, $m)) {
				$admin_need_login = $m[1] == '?';
				if ($admin_need_login && OIDplus::authUtils()->isAdminLoggedIn()) {
					$logEvent->addTarget(new OIDplusLogTargetUser($severity_online, 'admin'));
				} else if (!$admin_need_login) {
					$logEvent->addTarget(new OIDplusLogTargetUser($severity, 'admin'));
				}
			}

			// Unexpected
			else {
				throw new OIDplusException(_L('Unexpected logger component "%1" in mask code "%2"',$maskcode,$maskcodes));
			}
		}

		// Now write the log message

		$result = false;

		foreach ($loggerPlugins as $plugin) {
			$reason = '';
			if ($plugin->available($reason)) {
				$result |= $plugin->log($logEvent);
			}
		}

		return $result;
	}
}
