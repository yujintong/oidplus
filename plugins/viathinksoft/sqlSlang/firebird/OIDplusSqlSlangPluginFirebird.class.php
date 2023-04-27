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

class OIDplusSqlSlangPluginFirebird extends OIDplusSqlSlangPlugin {

	/**
	 * @return string
	 */
	public static function id(): string {
		return 'firebird';
	}

	/**
	 * @return string
	 */
	public function sqlDate(): string {
		// Firebird 3 :  LOCALTIMESTAMP == current_timestamp
		// Firebird 4 :  LOCALTIMESTAMP is without timezone and
		//               current_timestamp is with timezone.
		// PDO seems to have big problems with the "time stamp with time zone"
		// data type, since the plugin "adminPages => Systeminfo" shows an
		// empty string for "select current_timestamp from ###config".
		// Passing current_timestamp into a "insert into" query works however...
		// For now, we use LOCALTIMESTAMP. It does not seem to make a difference
		//return 'current_timestamp';
		return 'LOCALTIMESTAMP';
	}

	/**
	 * @param OIDplusDatabaseConnection $db
	 * @return bool
	 */
	public function detect(OIDplusDatabaseConnection $db): bool {
		try {
			return $db->query('select * from rdb$character_sets;')->num_rows() > 0;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * @param OIDplusDatabaseConnection $db
	 * @return int
	 * @throws OIDplusException
	 */
	public function insert_id(OIDplusDatabaseConnection $db): int {

		// TODO: IMPLEMENT FOR FIREBIRD!
		return 0;

	}

	/**
	 * @param string $cont
	 * @param string $table
	 * @param string $prefix
	 * @return string
	 */
	public function setupSetTablePrefix(string $cont, string $table, string $prefix): string {
		$table = strtoupper($table);
		$prefix = strtoupper($prefix);
		return str_replace('"'.$table.'"', '"'.$prefix.$table.'"', $cont);
	}

	/**
	 * @param string $database
	 * @return string
	 */
	public function setupCreateDbIfNotExists(string $database): string {
		// TODO! Implement
		return "";
	}

	/**
	 * @param string $database
	 * @return string
	 */
	public function setupUseDatabase(string $database): string {
		// TODO! Implement
		return "";
	}

	/**
	 * @param string $expr1
	 * @param string $expr2
	 * @return string
	 */
	public function isNullFunction(string $expr1, string $expr2): string {
		return "COALESCE($expr1, $expr2)";
	}

	/**
	 * @param string $sql
	 * @return string
	 */
	public function filterQuery(string $sql): string {
		// "select 1" is not valid. You need to add "from RDB$DATABASE"
		if ((stripos($sql,'select') !== false) && (stripos($sql,'from') === false)) {
			$sql .= ' from RDB$DATABASE';
		}

		//Value is a keyword and cannot be used as column name
		$sql = str_ireplace('value', '"VALUE"', $sql);
		$sql = str_ireplace('"VALUE"s', 'values', $sql);
		return $sql;
	}

	/**
	 * @param bool $bool
	 * @return string
	 */
	public function getSQLBool(bool $bool): string {
		return $bool ? '1' : '0';
	}

	/**
	 * @param string $str
	 * @return string
	 */
	public function escapeString(string $str): string {
		return str_replace("'", "''", $str);
	}
}
