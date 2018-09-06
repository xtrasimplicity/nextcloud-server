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


use OCP\Dashboard\IDashboardWidget;


/**
 * @since 15.0.0
 *
 * Interface IWidgetRequest
 *
 * @package OCP\Dashboard\Model
 */
interface IWidgetRequest {

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
	 * @return $this;
	 */
	public function setWidgetId(string $widgetId): IWidgetRequest;

	/**
	 * @since 15.0.0
	 *
	 * @return IDashboardWidget
	 */
	public function getWidget(): IDashboardWidget;

	/**
	 * @since 15.0.0
	 *
	 * @param IDashboardWidget $widget
	 *
	 * @return $this
	 */
	public function setWidget(IDashboardWidget $widget): IWidgetRequest;

	/**
	 * string to ident the request sent using the API from the front-end
	 *
	 *  net.requestWidget(
	 *    {
	 *     widget: widgetId,
	 *     request: request
	 *    },
	 *    callback);
	 *
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getRequest(): string;

	/**
	 * @since 15.0.0
	 *
	 * @param string $request
	 *
	 * @return $this
	 */
	public function setRequest(string $request): IWidgetRequest;

	/**
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function getResult(): array;

	/**
	 * set the result array that will be sent to the callback (front-end)
	 *
	 * @since 15.0.0
	 *
	 * @param array $result
	 *
	 * @return $this
	 */
	public function setResult(array $result): IWidgetRequest;

	/**
	 * add a result (as string)
	 *
	 * @since 15.0.0
	 *
	 * @param string $key
	 * @param string $result
	 *
	 * @return $this
	 */
	public function addResult(string $key, string $result): IWidgetRequest;

	/**
	 * add a result (as array)
	 *
	 * @since 15.0.0
	 *
	 * @param string $key
	 * @param array $result
	 *
	 * @return $this
	 */
	public function addResultArray(string $key, array $result): IWidgetRequest;

}

