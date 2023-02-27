<?php

/*
 * OIDplus 2.0
 * Copyright 2023 Daniel Marschall, ViaThinkSoft
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

class OIDplusAuthPluginCrypt extends OIDplusAuthPlugin {

	public function verify(OIDplusRAAuthInfo $authInfo, $check_password) {
		$authKey = $authInfo->getAuthKey();
		return password_verify($check_password, $authKey);
	}

	public function generate($password): OIDplusRAAuthInfo {
		$hashalgo = PASSWORD_SHA512; // choose the best out of crypt()
		$calc_authkey = vts_password_hash($password, $hashalgo);
		return new OIDplusRAAuthInfo($calc_authkey);
	}

	public function availableForHash(&$reason): bool {
		return function_exists('vts_password_hash');
	}

	public function availableForVerify(&$reason): bool {
		return function_exists('vts_password_verify');
	}

}
