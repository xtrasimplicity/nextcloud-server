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
 * Interface IWidgetSettings
 *
 * @package OCP\Dashboard\Model
 */
interface IWidgetSettings {

	/**
	 * @since 15.0.0
	 *
	 * IWidgetSettings constructor.
	 *
	 * @param string $userId
	 * @param string $widgetId
	 */
	public function __construct(string $widgetId, string $userId);

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getUserId(): string;

	/**
	 * @since 15.0.0
	 *
	 * @param string $userId
	 *
	 * @return $this
	 */
	public function setUserId(string $userId): IWidgetSettings;

	/**
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getWidgetId(): string;

	/**
	 * @param string $widgetId
	 *
	 * @return $this
	 */
	public function setWidgetId(string $widgetId): IWidgetSettings;

	/**
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function getPosition(): array;

	/**
	 * @since 15.0.0
	 *
	 * @param array $position
	 *
	 * @return $this
	 */
	public function setPosition(array $position): IWidgetSettings;

	/**
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function getSettings(): array;

	/**
	 * @param array $settings
	 *
	 * @return $this
	 */
	public function setSettings(array $settings): IWidgetSettings;

	/**
	 * @since 15.0.0
	 *
	 * @return bool
	 */
	public function isEnabled(): bool;

	/**
	 * @since 15.0.0
	 *
	 * @param bool $enabled
	 *
	 * @return $this
	 */
	public function setEnabled(bool $enabled): IWidgetSettings;

}

