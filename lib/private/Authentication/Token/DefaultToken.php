<?php

declare(strict_types=1);

/**
 * @copyright Copyright (c) 2016, ownCloud, Inc.
 *
 * @author Christoph Wurst <christoph@winzerhof-wurst.at>
 * @author Daniel Kesselberg <mail@danielkesselberg.de>
 * @author Robin Appelman <robin@icewind.nl>
 * @author Roeland Jago Douma <roeland@famdouma.nl>
 *
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 *
 */
namespace OC\Authentication\Token;

use OCP\AppFramework\Db\Entity;

/**
 * @method void setId(int $id)
 * @method void setUid(string $uid);
 * @method void setLoginName(string $loginname)
 * @method string getToken()
 * @method void setType(int $type)
 * @method int getType()
 * @method void setRemember(int $remember)
 * @method void setLastActivity(int $lastactivity)
 * @method int getLastActivity()
 * @method void setVersion(int $version)
 */
class DefaultToken extends Entity implements INamedToken {
	public const VERSION = 1;

	/** @var string user UID */
	protected $uid;

	/** @var string login name used for generating the token */
	protected $loginName;

	/** @var string encrypted user password */
	protected $password;

	/** @var string token name (e.g. browser/OS) */
	protected $name;

	/** @var string */
	protected $token;

	/** @var int */
	protected $type;

	/** @var int */
	protected $remember;

	/** @var int */
	protected $lastActivity;

	/** @var int */
	protected $lastCheck;

	/** @var string */
	protected $scope;

	/** @var int */
	protected $expires;

	/** @var int */
	protected $version;

	public function __construct() {
		$this->addType('uid', 'string');
		$this->addType('loginName', 'string');
		$this->addType('password', 'string');
		$this->addType('name', 'string');
		$this->addType('token', 'string');
		$this->addType('type', 'int');
		$this->addType('remember', 'int');
		$this->addType('lastActivity', 'int');
		$this->addType('lastCheck', 'int');
		$this->addType('scope', 'string');
		$this->addType('expires', 'int');
		$this->addType('version', 'int');
	}

	public function getId(): int {
		return $this->id;
	}

	public function getUID(): string {
		return $this->uid;
	}

	/**
	 * Get the login name used when generating the token
	 *
	 * @return string
	 */
	public function getLoginName(): string {
		return parent::getLoginName();
	}

	/**
	 * Get the (encrypted) login password
	 *
	 * @return string|null
	 */
	public function getPassword() {
		return parent::getPassword();
	}

	public function jsonSerialize(): array {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'lastActivity' => $this->lastActivity,
			'type' => $this->type,
			'scope' => $this->getScopeAsArray()
		];
	}

	/**
	 * Get the timestamp of the last password check
	 *
	 * @return int
	 */
	public function getLastCheck(): int {
		return parent::getLastCheck();
	}

	/**
	 * Get the timestamp of the last password check
	 *
	 * @param int $time
	 */
	public function setLastCheck(int $time) {
		parent::setLastCheck($time);
	}

	public function getScope(): string {
		$scope = parent::getScope();
		if ($scope === null) {
			return '';
		}

		return $scope;
	}

	public function getScopeAsArray(): array {
		$scope = json_decode($this->getScope(), true);
		if (!$scope) {
			return [
				'filesystem' => true
			];
		}
		return $scope;
	}

	public function setScope($scope) {
		if (\is_array($scope)) {
			parent::setScope(json_encode($scope));
		} else {
			parent::setScope((string)$scope);
		}
	}

	public function getName(): string {
		return parent::getName();
	}

	public function setName(string $name): void {
		parent::setName($name);
	}

	public function getRemember(): int {
		return parent::getRemember();
	}

	public function setToken(string $token) {
		parent::setToken($token);
	}

	public function setPassword(string $password = null) {
		parent::setPassword($password);
	}

	public function setExpires($expires) {
		parent::setExpires($expires);
	}

	/**
	 * @return int|null
	 */
	public function getExpires() {
		return parent::getExpires();
	}
}
