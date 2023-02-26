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

class OIDplusGui extends OIDplusBaseClass {

	public static function generateContentPage($id) {
		$out = array();

		$handled = false;
		$out['title'] = '';
		$out['icon'] = '';
		$out['text'] = '';

		foreach (OIDplus::getPagePlugins() as $plugin) {
			try {
				$plugin->gui($id, $out, $handled);
			} catch (\Exception $e) {
				$out['title'] = _L('Error');
				$out['icon'] = 'img/error.png';
				$out['text'] = $e->getMessage();
			}
			if ($handled) break;
		}

		if (!$handled) {
			if (isset($_SERVER['SCRIPT_FILENAME']) && (strtolower(basename($_SERVER['SCRIPT_FILENAME'])) !== 'ajax.php')) { // don't send HTTP error codes in ajax.php, because we want a page and not a JavaScript alert box, when someone enters an invalid OID in the GoTo-Box
				if (PHP_SAPI != 'cli') @http_response_code(404);
			}
			$out['title'] = _L('Error');
			$out['icon'] = 'img/error.png';
			$out['text'] = _L('The resource cannot be found.');
		}

		return $out;
	}

	public static function link($goto, $new_window=false): string {
		if ($new_window) {
			return 'href="?goto='.urlencode($goto).'" target="_blank"';
		} else {
			if (strpos($goto, '#') !== false) {
				list($goto, $anchor) = explode('#', $goto, 2);
				return 'href="?goto='.urlencode($goto).'#'.htmlentities($anchor).'" onclick="openOidInPanel('.js_escape($goto).', true, '.js_escape($anchor).'); return false;"';
			} else {
				return 'href="?goto='.urlencode($goto).'" onclick="openOidInPanel('.js_escape($goto).', true); return false;"';
			}
		}
	}

	public static function getLanguageBox($goto, $useJs) {
		$out = '';
		$out .= '<div id="languageBox">';
		$langbox_entries = array();
		$non_default_languages = 0;
		foreach (OIDplus::getAllPluginManifests('language') as $pluginManifest) {
			$flag = $pluginManifest->getLanguageFlag();
			$code = $pluginManifest->getLanguageCode();
			if ($code != OIDplus::getDefaultLang()) $non_default_languages++;
			if ($code == OIDplus::getCurrentLang()) {
				$class = 'lng_flag';
			} else {
				$class = 'lng_flag picture_ghost';
			}
			$add = (!is_null($goto)) ? '&amp;goto='.urlencode($goto) : '';

			$dirs = glob(OIDplus::localpath().'plugins/'.'*'.'/language/'.$code.'/');

			if (count($dirs) > 0) {
				$dir = substr($dirs[0], strlen(OIDplus::localpath()));
				$langbox_entries[$code] = '<span class="lang_flag_bg"><a '.($useJs ? 'onclick="return !setLanguage(\''.$code.'\')" ' : '').'href="?lang='.$code.$add.'"><img src="'.OIDplus::webpath(null,OIDplus::PATH_RELATIVE).$dir.$flag.'" alt="'.$pluginManifest->getName().'" title="'.$pluginManifest->getName().'" class="'.$class.'" id="lng_flag_'.$code.'" height="20"></a></span> ';
			}
		}
		if ($non_default_languages > 0) {
			foreach ($langbox_entries as $ent) {
				$out .= "$ent\n\t\t";
			}
		}
		$out .= '</div>';
		return $out;
	}

	public static function html_exception_handler($exception) {
		if ($exception instanceof OIDplusConfigInitializationException) {
			echo '<!DOCTYPE HTML>';
			echo '<html><head><title>'.htmlentities(_L('OIDplus initialization error')).'</title></head><body>';
			echo '<h1>'.htmlentities(_L('OIDplus initialization error')).'</h1>';
			echo '<p>'.htmlentities($exception->getMessage(), ENT_SUBSTITUTE).'</p>';
			$msg = _L('Please check the file %1','<b>userdata/baseconfig/config.inc.php</b>');
			if (is_dir(__DIR__ . '/../../setup')) {
				$msg .= ' '._L('or run <a href="%1">setup</a> again',OIDplus::webpath(null,OIDplus::PATH_RELATIVE).'setup/');
			}
			echo '<p>'.htmlentities($msg).'</p>';
			echo self::getExceptionTechInfo($exception);
			echo '</body></html>';
		} else {
			echo '<!DOCTYPE HTML>';
			echo '<html><head><title>'.htmlentities(_L('OIDplus error')).'</title></head><body>';
			echo '<h1>'.htmlentities(_L('OIDplus error')).'</h1>';
			// ENT_SUBSTITUTE because ODBC drivers might return ANSI instead of UTF-8 stuff
			echo '<p>'.htmlentities($exception->getMessage(), ENT_SUBSTITUTE).'</p>';
			echo self::getExceptionTechInfo($exception);
			echo '</body></html>';
		}
	}

	private static function getExceptionTechInfo($exception) {
		$out = '';
		$out .= '<p><b>'.htmlentities(_L('Technical information about the problem')).':</b></p>';
		$out .= '<pre>';
		$out .= get_class($exception)."\n";
		$out .= _L('at file %1 (line %2)',$exception->getFile(),"".$exception->getLine())."\n\n";
		$out .= _L('Stacktrace').":\n";
		$out .= htmlentities($exception->getTraceAsString());
		$out .= '</pre>';
		return $out;
	}

	public function tabBarStart() {
		return '<ul class="nav nav-tabs" id="myTab" role="tablist">';
	}

	public function tabBarEnd() {
		return '</ul>';
	}

	public function tabBarElement($id, $title, $active) {
		// data-bs-toggle is for Bootstrap 5
		// data-toggle is for Bootstrap 4 (InternetExplorer compatibility)
		return '<li class="nav-item"><a class="nav-link'.($active ? ' active' : '').'" id="'.$id.'-tab" data-bs-toggle="tab" data-toggle="tab" href="#'.$id.'" role="tab" aria-controls="'.$id.'" aria-selected="'.($active ? 'true' : 'false').'">'.$title.'</a></li>';
	}

	public function tabContentStart() {
		return '<div class="tab-content" id="myTabContent">';
	}

	public function tabContentEnd() {
		return '</div>';
	}

	public function tabContentPage($id, $content, $active) {
		return '<div class="tab-pane fade'.($active ? ' show active' : '').'" id="'.$id.'" role="tabpanel" aria-labelledby="'.$id.'-tab">'.$content.'</div>';
	}

	public function combine_systemtitle_and_pagetitle($systemtitle, $pagetitle) {
		// Please also change the function in oidplus_base.js
		if ($systemtitle == $pagetitle) {
			return $systemtitle;
		} else {
			return $pagetitle . ' - ' . $systemtitle;
		}
	}

	private function getCommonHeadElems($title) {
		// Get theme color (color of title bar)
		$design_plugin = OIDplus::getActiveDesignPlugin();
		$theme_color = is_null($design_plugin) ? '' : $design_plugin->getThemeColor();

		$head_elems = array();
		$head_elems[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">';
		if (OIDplus::baseConfig()->getValue('DATABASE_PLUGIN','') !== '') {
			$head_elems[] = '<meta name="OIDplus-SystemTitle" content="'.htmlentities(OIDplus::config()->getValue('system_title')).'">'; // Do not remove. This meta tag is acessed by oidplus_base.js
		}
		if ($theme_color != '') {
			$head_elems[] = '<meta name="theme-color" content="'.htmlentities($theme_color).'">';
		}
		$head_elems[] = '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
		$head_elems[] = '<title>'.htmlentities($title).'</title>';
		$head_elems[] = '<script src="'.OIDplus::webpath(null, OIDplus::PATH_RELATIVE).'polyfill.min.js.php"></script>';
		$head_elems[] = '<script src="'.OIDplus::webpath(null, OIDplus::PATH_RELATIVE).'oidplus.min.js.php?noBaseConfig=1" type="text/javascript"></script>';
		$head_elems[] = '<link rel="stylesheet" href="'.OIDplus::webpath(null, OIDplus::PATH_RELATIVE).'oidplus.min.css.php?noBaseConfig=1">';
		$head_elems[] = '<link rel="shortcut icon" type="image/x-icon" href="'.OIDplus::webpath(null, OIDplus::PATH_RELATIVE).'favicon.ico.php">';
		if (OIDplus::baseConfig()->exists('CANONICAL_SYSTEM_URL')) {
			$head_elems[] = '<link rel="canonical" href="'.htmlentities(OIDplus::canonicalURL()).OIDplus::webpath(null, OIDplus::PATH_RELATIVE).'">';
		}

		return $head_elems;
	}

	public function showMainPage($page_title_1, $page_title_2, $static_icon, $static_content, $extra_head_tags=array(), $static_node_id='') {
		$head_elems = $this->getCommonHeadElems($page_title_1);
		$head_elems = array_merge($head_elems, $extra_head_tags);

		$plugins = OIDplus::getPagePlugins();
		foreach ($plugins as $plugin) {
			$plugin->htmlHeaderUpdate($head_elems);
		}

		# ---

		$out  = "<!DOCTYPE html>\n";

		$out .= "<html lang=\"".substr(OIDplus::getCurrentLang(),0,2)."\">\n";
		$out .= "<head>\n";
		$out .= "\t".implode("\n\t",$head_elems)."\n";
		$out .= "</head>\n";

		$out .= "<body>\n";

		$out .= '<div id="loading" style="display:none">Loading&#8230;</div>';

		$out .= '<div id="frames">';
		$out .= '<div id="content_window" class="borderbox">';

		$out .= '<h1 id="real_title">';
		if ($static_icon != '') $out .= '<img src="'.htmlentities($static_icon).'" width="48" height="48" alt=""> ';
		$out .= htmlentities($page_title_2).'</h1>';
		$out .= '<div id="real_content">'.$static_content.'</div>';
		if ((!isset($_SERVER['REQUEST_METHOD'])) || ($_SERVER['REQUEST_METHOD'] == 'GET')) {
			$out .= '<br><p><img src="img/share.png" width="15" height="15" alt="'._L('Share').'"> <a href="?goto='.htmlentities($static_node_id).'" id="static_link" class="gray_footer_font">'._L('Static link to this page').'</a>';
			$out .= '</p>';
		}
		$out .= '<br>';

		$out .= '</div>';

		$out .= '<div id="system_title_bar">';

		$out .= '<div id="system_title_menu" onclick="mobileNavButtonClick(this)" onmouseenter="mobileNavButtonHover(this)" onmouseleave="mobileNavButtonHover(this)">';
		$out .= '	<div id="bar1"></div>';
		$out .= '	<div id="bar2"></div>';
		$out .= '	<div id="bar3"></div>';
		$out .= '</div>';

		$out .= '<div id="system_title_text">';
		$out .= '	<a '.OIDplus::gui()->link('oidplus:system').' id="system_title_a">';
		$out .= '		<span id="system_title_logo"></span>';
		$out .= '		<span id="system_title_1">'.htmlentities(OIDplus::getEditionInfo()['vendor'].' OIDplus 2.0').'</span><br>';
		$out .= '		<span id="system_title_2">'.htmlentities(OIDplus::config()->getValue('system_title')).'</span>';
		$out .= '	</a>';
		$out .= '</div>';

		$out .= '</div>';

		$out .= OIDplus::gui()->getLanguageBox($static_node_id, true);

		$out .= '<div id="gotobox">';
		$out .= '<input type="text" name="goto" id="gotoedit" value="'.htmlentities($static_node_id).'">';
		$out .= '<input type="button" value="'._L('Go').'" onclick="gotoButtonClicked()" id="gotobutton">';
		$out .= '</div>';

		$out .= '<div id="oidtree" class="borderbox">';
		//$out .= '<noscript>';
		//$out .= '<p><b>'._L('Please enable JavaScript to use all features').'</b></p>';
		//$out .= '</noscript>';
		$out .= OIDplus::menuUtils()->nonjs_menu();
		$out .= '</div>';

		$out .= '</div>';

		$out .= "\n</body>\n";
		$out .= "</html>\n";

		# ---

		$plugins = OIDplus::getPagePlugins();
		foreach ($plugins as $plugin) {
			$plugin->htmlPostprocess($out);
		}

		return $out;
	}

	public function showSimplePage($page_title_1, $page_title_2, $static_icon, $static_content, $extra_head_tags=array()) {
		$head_elems = $this->getCommonHeadElems($page_title_1);
		$head_elems = array_merge($head_elems, $extra_head_tags);

		# ---

		$out  = "<!DOCTYPE html>\n";

		$out .= "<html lang=\"".substr(OIDplus::getCurrentLang(),0,2)."\">\n";
		$out .= "<head>\n";
		$out .= "\t".implode("\n\t",$head_elems)."\n";
		$out .= "</head>\n";

		$out .= "<body>\n";

		$out .= '<div id="loading" style="display:none">Loading&#8230;</div>';

		$out .= '<div id="frames">';
		$out .= '<div id="content_window" class="borderbox">';

		$out .= '<h1 id="real_title">';
		if ($static_icon != '') $out .= '<img src="'.htmlentities($static_icon).'" width="48" height="48" alt=""> ';
		$out .= htmlentities($page_title_2).'</h1>';
		$out .= '<div id="real_content">'.$static_content.'</div>';
		$out .= '<br>';

		$out .= '</div>';

		$out .= '<div id="system_title_bar">';

		$out .= '<div id="system_title_text">';
		$out .= '	<span id="system_title_logo"></span>';
		$out .= '	<span id="system_title_1">'.htmlentities(OIDplus::getEditionInfo()['vendor'].' OIDplus 2.0').'</span><br>';
		$out .= '	<span id="system_title_2">'.htmlentities($page_title_1).'</span>';
		$out .= '</div>';

		$out .= '</div>';

		$out .= OIDplus::gui()->getLanguageBox(null, true);

		$out .= '</div>';

		$out .= "\n</body>\n";
		$out .= "</html>\n";

		# ---

		return $out;
	}

}
