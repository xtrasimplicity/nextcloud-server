<?php
declare(strict_types=1);


/**
 * Nextcloud - Dashboard App
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Maxence Lange <maxence@artificial-owl.com>
 * @copyright 2018, Maxence Lange <maxence@artificial-owl.com>
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCP\Dashboard\Model;


/**
 * @since 15.0.0
 *
 * Interface IWidgetEvent
 *
 * @package OCP\Dashboard\Model
 */
interface IWidgetEvent {

	const BROADCAST_USER = 'user';
	const BROADCAST_GROUP = 'group';
	const BROADCAST_GLOBAL = 'global';

	/**
	 * @since 15.0.0
	 *
	 * @return int
	 */
	public function getId(): int;

	/**
	 * @since 15.0.0
	 *
	 * @param int $id
	 *
	 * @return $this
	 */
	public function setId(int $id): IWidgetEvent;

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getWidgetId(): string;

	/**
	 * @since 15.0.0
	 *
	 * @param string $widgetId
	 *
	 * @return $this
	 */
	public function setWidgetId(string $widgetId): IWidgetEvent;

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getBroadcast(): string;

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getRecipient(): string;

	/**
	 * @since 15.0.0
	 *
	 * @param string $broadcast
	 * @param string $recipient
	 *
	 * @return $this
	 */
	public function setRecipient(string $broadcast, string $recipient): IWidgetEvent;

	/**
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function getPayload(): array;

	/**
	 * @since 15.0.0
	 *
	 * @param array $payload
	 *
	 * @return $this
	 */
	public function setPayload(array $payload): IWidgetEvent;

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getUniqueId(): string;

	/**
	 * @since 15.0.0
	 *
	 * @param string $uniqueId
	 *
	 * @return $this
	 */
	public function setUniqueId(string $uniqueId): IWidgetEvent;

	/**
	 * @since 15.0.0
	 *
	 * @return int
	 */
	public function getCreation(): int;

	/**
	 * @since 15.0.0
	 *
	 * @param int $creation
	 *
	 * @return $this
	 */
	public function setCreation(int $creation): IWidgetEvent;

}

