<?php

/*
 * OIDplus 2.0
 * Copyright 2019 - 2022 Daniel Marschall, ViaThinkSoft
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

if (!defined('INSIDE_OIDPLUS')) die();

class OIDplusPagePublicAntiSpamFilter extends OIDplusPagePluginPublic {

	public function htmlPostprocess(&$html) {
		$html = preg_replace_callback(
			'|<a\s([^>]*)href="mailto:([^"]+)"([^>]*)>([^<]*)</a>|ismU',
			function ($treffer) {
				$email = $treffer[2];
				$text = $treffer[4];
				return OIDplus::mailUtils()->secureEmailAddress($email, $text, 1); // AntiSpam
			}, $html);
	}

}
