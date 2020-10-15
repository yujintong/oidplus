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

class OIDplusPageRaChangeEMail extends OIDplusPagePluginRa {

	public function action($actionID, $params) {
		if ($actionID == 'change_ra_email') {
			if (!OIDplus::config()->getValue('allow_ra_email_change') && !OIDplus::authUtils()::isAdminLoggedIn()) {
				throw new OIDplusException(_L('This functionality has been disabled by the administrator.'));
			}

			$old_email = $params['old_email'];
			$new_email = $params['new_email'];

			if (!OIDplus::authUtils()::isRaLoggedIn($old_email) && !OIDplus::authUtils()::isAdminLoggedIn()) {
				throw new OIDplusException(_L('Authentication error. Please log in as admin, or as the RA to update its email address.'));
			}

			if (!OIDplus::mailUtils()->validMailAddress($new_email)) {
				throw new OIDplusException(_L('eMail address is invalid.'));
			}

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($old_email));
			if ($res->num_rows() == 0) {
				throw new OIDplusException(_L('eMail address does not exist anymore. It was probably already changed.'));
			}

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($new_email));
			if ($res->num_rows() > 0) {
				throw new OIDplusException(_L('eMail address is already used by another RA. To merge accounts, please contact the superior RA of your objects and request an owner change of your objects.'));
			}

			if (OIDplus::authUtils()::isAdminLoggedIn()) {
				OIDplus::logger()->log("[WARN]RA($old_email)!+[INFO]RA($new_email)!+[OK]A!", "Admin changed email address '$old_email' to '$new_email'");

				$ra_was_logged_in = OIDplus::authUtils()::isRaLoggedIn($old_email);

				$ra = new OIDplusRA($old_email);
				$ra->change_email($new_email);

				OIDplus::db()->query("update ###objects set ra_email = ? where ra_email = ?", array($new_email, $old_email));

				if ($ra_was_logged_in) {
					OIDplus::authUtils()->raLogout($old_email);
					OIDplus::authUtils()->raLogin($new_email);
				}

				return array("status" => 0);
			} else {
				OIDplus::logger()->log("[INFO]RA($old_email)!+RA($new_email)!", "Requested email address change from '$old_email' to '$new_email'");

				$timestamp = time();
				$activate_url = OIDplus::getSystemUrl() . '?goto='.urlencode('oidplus:activate_new_ra_email$'.$old_email.'$'.$new_email.'$'.$timestamp.'$'.OIDplus::authUtils()::makeAuthKey('activate_new_ra_email;'.$old_email.';'.$new_email.';'.$timestamp));

				$message = file_get_contents(__DIR__ . '/change_request_email.tpl');
				$message = str_replace('{{SYSTEM_URL}}', OIDplus::getSystemUrl(), $message);
				$message = str_replace('{{SYSTEM_TITLE}}', OIDplus::config()->getValue('system_title'), $message);
				$message = str_replace('{{ADMIN_EMAIL}}', OIDplus::config()->getValue('admin_email'), $message);
				$message = str_replace('{{OLD_EMAIL}}', $old_email, $message);
				$message = str_replace('{{NEW_EMAIL}}', $new_email, $message);
				$message = str_replace('{{ACTIVATE_URL}}', $activate_url, $message);
				OIDplus::mailUtils()->sendMail($new_email, OIDplus::config()->getValue('system_title').' - Change email request', $message);

				return array("status" => 0);
			}
		}

		else if ($actionID == 'activate_new_ra_email') {
			if (!OIDplus::config()->getValue('allow_ra_email_change')) {
				throw new OIDplusException(_L('This functionality has been disabled by the administrator.'));
			}

			$old_email = $params['old_email'];
			$new_email = $params['new_email'];
			$password = $params['password'];

			$auth = $params['auth'];
			$timestamp = $params['timestamp'];

			if (!OIDplus::authUtils()::validateAuthKey('activate_new_ra_email;'.$old_email.';'.$new_email.';'.$timestamp, $auth)) {
				throw new OIDplusException(_L('Invalid auth key'));
			}

			if ((OIDplus::config()->getValue('max_ra_email_change_time') > 0) && (time()-$timestamp > OIDplus::config()->maxEmailChangeTime())) {
				throw new OIDplusException(_L('Activation link expired!'));
			}

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($old_email));
			if ($res->num_rows() == 0) {
				throw new OIDplusException(_L('eMail address does not exist anymore. It was probably already changed.'));
			}

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($new_email));
			if ($res->num_rows() > 0) {
				throw new OIDplusException(_L('eMail address is already used by another RA. To merge accounts, please contact the superior RA of your objects and request an owner change of your objects.'));
			}

			$ra = new OIDplusRA($old_email);
			if (!$ra->checkPassword($password)) {
				throw new OIDplusException(_L('Wrong password'));
			}

			$ra->change_email($new_email);

			OIDplus::db()->query("update ###objects set ra_email = ? where ra_email = ?", array($new_email, $old_email));

			OIDplus::authUtils()->raLogout($old_email);
			OIDplus::authUtils()->raLogin($new_email);

			OIDplus::logger()->log("[OK]RA($new_email)!+RA($old_email)!", "RA '$old_email' has changed their email address to '$new_email'");

			$message = file_get_contents(__DIR__ . '/email_change_confirmation.tpl');
			$message = str_replace('{{SYSTEM_URL}}', OIDplus::getSystemUrl(), $message);
			$message = str_replace('{{SYSTEM_TITLE}}', OIDplus::config()->getValue('system_title'), $message);
			$message = str_replace('{{ADMIN_EMAIL}}', OIDplus::config()->getValue('admin_email'), $message);
			$message = str_replace('{{OLD_EMAIL}}', $old_email, $message);
			$message = str_replace('{{NEW_EMAIL}}', $new_email, $message);
			OIDplus::mailUtils()->sendMail($old_email, OIDplus::config()->getValue('system_title').' - eMail address changed', $message);

			return array("status" => 0);
		} else {
			throw new OIDplusException(_L('Unknown action ID'));
		}
	}

	public function init($html=true) {
		OIDplus::config()->prepareConfigKey('max_ra_email_change_time', 'Max RA email change time in seconds (0 = infinite)', '0', OIDplusConfig::PROTECTION_EDITABLE, function($value) {
			if (!is_numeric($value) || ($value < 0)) {
				throw new OIDplusException(_L('Please enter a valid value.'));
			}
		});
		OIDplus::config()->prepareConfigKey('allow_ra_email_change', 'Allow that RAs change their email address (0/1)', '1', OIDplusConfig::PROTECTION_EDITABLE, function($value) {
		        if (($value != '0') && ($value != '1')) {
		                throw new OIDplusException(_L('Please enter a valid value (0=no, 1=yes).'));
		        }
		});
	}

	public function gui($id, &$out, &$handled) {
		if (explode('$',$id)[0] == 'oidplus:change_ra_email') {
			$handled = true;

			$ra_email = explode('$',$id)[1];

			$out['title'] = _L('Change RA email');
			$out['icon'] = file_exists(__DIR__.'/icon_big.png') ? OIDplus::webpath(__DIR__).'icon_big.png' : '';

			if (!OIDplus::authUtils()::isRaLoggedIn($ra_email) && !OIDplus::authUtils()::isAdminLoggedIn()) {
				$out['icon'] = 'img/error_big.png';
				$out['text'] = '<p>'._L('You need to <a %1>log in</a> as the requested RA %2 or as admin.',OIDplus::gui()->link('oidplus:login'),'<b>'.htmlentities($ra_email).'</b>').'</p>';
				return;
			}

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($ra_email));
			if ($res->num_rows() == 0) {
				$out['icon'] = 'img/error_big.png';
				$out['text'] = _L('RA "%1" does not exist','<b>'.htmlentities($ra_email).'</b>');
				return;
			}

			if (!OIDplus::config()->getValue('allow_ra_email_change') && !OIDplus::authUtils()::isAdminLoggedIn()) {
				$out['icon'] = 'img/error_big.png';
				$out['text'] = '<p>'._L('This functionality has been disabled by the administrator.').'</p>';
				return;
			}

			$out['text'] .= '<p>'._L('Attention! Do NOT change your email address if you have logged in using Google/LDAP and not yet created an individual password (for regular login), otherwise you will lose access to your account!').'</p>';

			if (OIDplus::authUtils()::isAdminLoggedIn()) {
				$out['text'] .= '<form id="changeRaEmailForm" action="javascript:void(0);" action="javascript:void(0);" onsubmit="return changeRaEmailFormOnSubmit(true);">';
				$out['text'] .= '<input type="hidden" id="old_email" value="'.htmlentities($ra_email).'"/><br>';
				$out['text'] .= '<div><label class="padding_label">'._L('Old address').':</label><b>'.htmlentities($ra_email).'</b></div>';
				$out['text'] .= '<div><label class="padding_label">'._L('New address').':</label><input type="text" id="new_email" value=""/></div>';
				$out['text'] .= '<br><input type="submit" value="'._L('Change password').'"> '._L('(admin does not require email verification)').'</form>';
			} else {
				$out['text'] .= '<form id="changeRaEmailForm" action="javascript:void(0);" action="javascript:void(0);" onsubmit="return changeRaEmailFormOnSubmit(false);">';
				$out['text'] .= '<input type="hidden" id="old_email" value="'.htmlentities($ra_email).'"/><br>';
				$out['text'] .= '<div><label class="padding_label">'._L('Old address').':</label><b>'.htmlentities($ra_email).'</b></div>';
				$out['text'] .= '<div><label class="padding_label">'._L('New address').':</label><input type="text" id="new_email" value=""/></div>';
				$out['text'] .= '<br><input type="submit" value="'._L('Send new activation email').'"></form>';
			}
		} else if (explode('$',$id)[0] == 'oidplus:activate_new_ra_email') {
			$handled = true;

			$old_email = explode('$',$id)[1];
			$new_email = explode('$',$id)[2];
			$timestamp = explode('$',$id)[3];
			$auth = explode('$',$id)[4];

			if (!OIDplus::config()->getValue('allow_ra_email_change') && !OIDplus::authUtils()::isAdminLoggedIn()) {
				$out['icon'] = 'img/error_big.png';
				$out['text'] = '<p>'._L('This functionality has been disabled by the administrator.').'</p>';
				return;
			}

			$out['title'] = _L('Perform email address change');
			$out['icon'] = file_exists(__DIR__.'/icon_big.png') ? OIDplus::webpath(__DIR__).'icon_big.png' : '';

			$res = OIDplus::db()->query("select * from ###ra where email = ?", array($old_email));
			if ($res->num_rows() == 0) {
				$out['icon'] = 'img/error_big.png';
				$out['text'] = _L('eMail address does not exist anymore. It was probably already changed.');
			} else {
				$res = OIDplus::db()->query("select * from ###ra where email = ?", array($new_email));
				if ($res->num_rows() > 0) {
					$out['icon'] = 'img/error_big.png';
					$out['text'] = _L('eMail address is already used by another RA. To merge accounts, please contact the superior RA of your objects and request an owner change of your objects.');
				} else {
					if (!OIDplus::authUtils()::validateAuthKey('activate_new_ra_email;'.$old_email.';'.$new_email.';'.$timestamp, $auth)) {
						$out['icon'] = 'img/error_big.png';
						$out['text'] = _L('Invalid authorization. Is the URL OK?');
					} else {
						$out['text'] = '<p>'._L('Old eMail-Address').': <b>'.$old_email.'</b></p>
						<p>'._L('New eMail-Address').': <b>'.$new_email.'</b></p>

						 <form id="activateNewRaEmailForm" action="javascript:void(0);" onsubmit="return activateNewRaEmailFormOnSubmit();">
					    <input type="hidden" id="old_email" value="'.htmlentities($old_email).'"/>
					    <input type="hidden" id="new_email" value="'.htmlentities($new_email).'"/>
					    <input type="hidden" id="timestamp" value="'.htmlentities($timestamp).'"/>
					    <input type="hidden" id="auth" value="'.htmlentities($auth).'"/>

					    <div><label class="padding_label">'._L('Please verify your password').':</label><input type="password" id="password" value=""/></div>
					    <br><input type="submit" value="'._L('Change email address').'">
					  </form>';
					}
				}
			}
		}
	}

	public function tree(&$json, $ra_email=null, $nonjs=false, $req_goto='') {
		if (!$ra_email) return false;
		if (!OIDplus::authUtils()::isRaLoggedIn($ra_email) && !OIDplus::authUtils()::isAdminLoggedIn()) return false;

		if (file_exists(__DIR__.'/treeicon.png')) {
			$tree_icon = OIDplus::webpath(__DIR__).'treeicon.png';
		} else {
			$tree_icon = null; // default icon (folder)
		}

		$json[] = array(
			'id' => 'oidplus:change_ra_email$'.$ra_email,
			'icon' => $tree_icon,
			'text' => _L('Change email address')
		);

		return true;
	}

	public function tree_search($request) {
		return false;
	}
}
