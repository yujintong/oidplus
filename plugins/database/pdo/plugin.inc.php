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

if (!defined('IN_OIDPLUS')) die();

class OIDplusDataBasePluginPDO extends OIDplusDataBasePlugin {
	private $pdo;
	private $last_query;
	private $prepare_cache = array();

	public static function getPluginInformation(): array {
		$out = array();
		$out['name'] = 'PDO';
		$out['author'] = 'ViaThinkSoft';
		$out['version'] = null;
		$out['descriptionHTML'] = null;
		return $out;
	}

	public static function name(): string {
		return "PDO";
	}

	public function query($sql, $prepared_args=null): OIDplusQueryResult {
		$this->last_query = $sql;
		if (is_null($prepared_args)) {
			$res = $this->pdo->query($sql);

			if ($res === false) {
				throw new OIDplusSQLException($sql, $this->error());
			} else {
				return new OIDplusQueryResultPDO($res);
			}
		} else {
			// TEST: Emulate the prepared statement
			/*
			foreach ($prepared_args as $arg) {
				$needle = '?';
				$replace = "'$arg'"; // TODO: types
				$pos = strpos($sql, $needle);
				if ($pos !== false) {
					$sql = substr_replace($sql, $replace, $pos, strlen($needle));
				}
			}
			return OIDplusQueryResultPDO($this->pdo->query($sql));
			*/

			if (!is_array($prepared_args)) {
				throw new Exception("'prepared_args' must be either NULL or an ARRAY.");
			}
			if (isset($this->prepare_cache[$sql])) {
				$ps = $this->prepare_cache[$sql];
			} else {
				$ps = $this->pdo->prepare($sql);
				if (!$ps) {
					throw new OIDplusSQLException($sql, 'Cannot prepare statement');
				}
				$this->prepare_cache[$sql] = $ps;
			}
			if (!$ps->execute($prepared_args)) {
				throw new OIDplusSQLException($sql, $this->error());
			}
			return new OIDplusQueryResultPDO($ps);
		}
	}

	public function insert_id(): int {
		return $this->pdo->lastInsertId();
	}

	public function error(): string {
		return $this->pdo->errorInfo()[2];
	}

	private $html = null;
	public function init($html = true): void {
		$this->html = $html;
	}

	public function connect(): void {
		try {
			$options = [
			#    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			    PDO::ATTR_EMULATE_PREPARES   => true,
			];

			// Try connecting to the database
			$this->pdo = new PDO(OIDPLUS_PDO_DSN, OIDPLUS_PDO_USERNAME, base64_decode(OIDPLUS_PDO_PASSWORD), $options);
		} catch (PDOException $e) {
			if ($this->html) {
				echo "<h1>Error</h1><p>Database connection failed! (".$e->getMessage().")</p>";
				if (is_dir(__DIR__.'/../../../setup')) {
					echo '<p>If you believe that the login credentials are wrong, please run <a href="setup/">setup</a> again.</p>';
				}
			} else {
				echo "Error: Database connection failed! (".$e->getMessage().")";
				if (is_dir(__DIR__.'/../../../setup')) {
					echo ' If you believe that the login credentials are wrong, please run setup again.';
				}
			}
			die();
		}

		$this->query("SET NAMES 'utf8'");
		$this->afterConnect($this->html);
		$this->connected = true;
	}

	private $intransaction = false;

	public function transaction_begin(): void {
		if ($this->intransaction) throw new Exception("Nested transactions are not supported by this database plugin.");
		$this->pdo->beginTransaction();
		$this->intransaction = true;
	}

	public function transaction_commit(): void {
		$this->pdo->commit();
		$this->intransaction = false;
	}

	public function transaction_rollback(): void {
		$this->pdo->rollBack();
		$this->intransaction = false;
	}
}

class OIDplusQueryResultPDO extends OIDplusQueryResult {
	protected $no_resultset;
	protected $res;

	public function __construct($res) {
		$this->no_resultset = $res === false;
		$this->res = $res;
	}

	public function containsResultSet(): bool {
		return !$this->no_resultset;
	}

	public function num_rows(): int {
		if ($this->no_resultset) throw new Exception("The query has returned no result set (i.e. it was not a SELECT query)");
		return $this->res->rowCount();
	}

	public function fetch_array()/*: ?array*/ {
		if ($this->no_resultset) throw new Exception("The query has returned no result set (i.e. it was not a SELECT query)");
		$ret = $this->res->fetch(PDO::FETCH_ASSOC);
		if ($ret === false) $ret = null;
		return $ret;
	}

	public function fetch_object()/*: ?object*/ {
		if ($this->no_resultset) throw new Exception("The query has returned no result set (i.e. it was not a SELECT query)");
		$ret = $this->res->fetch(PDO::FETCH_OBJ);
		if ($ret === false) $ret = null;
		return $ret;
	}
}
