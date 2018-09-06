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


namespace OCP\Dashboard;


use OCP\Dashboard\Model\IWidgetRequest;
use OCP\Dashboard\Model\IWidgetSettings;

/**
 * @since 15.0.0
 *
 * Interface IDashboardWidget
 *
 * @package OCP\Dashboard
 */
interface IDashboardWidget {

	/**
	 * Should returns the (unique) Id of the widget.
	 *
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getId(): string;

	/**
	 * Should returns the [display] name of the widget.
	 *
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getName(): string;

	/**
	 * Should returns some text describing the widget.
	 * This description is displayed in the listing of the widget.
	 *
	 * @since 15.0.0
	 *
	 * @return string
	 */
	public function getDescription(): string;

	/**
	 * Should returns an array containing data to access the templates to be
	 * load with the widget:
	 *
	 * [
	 *   'app'      => (string) id of the app containing the widget,
	 *   'icon'     => (string) class of the icon,
	 *   'css'      => (string/array) path and name of CSS file(s),
	 *   'js'       => (string/array) path and name of JS file(s),
	 *   'content'  => (string) path to the HTML Template of the widget,
	 *   'function' => (string) JavaScript function to be called when
	 *                          loading the widget on the dashboard
	 * ];
	 *
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function getTemplate(): array;

	/**
	 * This method is called when a widget is loaded on the dashboard.
	 * A widget is 'loaded on the dashboard' when one of these conditions
	 * occurs:
	 *
	 * - the user is adding the widget on his dashboard,
	 * - the user already added the widget on his dashboard and he is opening
	 *   the dashboard app.
	 *
	 * @since 15.0.0
	 *
	 * @param IWidgetSettings $settings
	 */
	public function loadWidget(IWidgetSettings $settings);

	/**
	 * multi dimensional array containing settings of the widget.
	 *
	 * Listing of the entries that can be set in this array:
	 *
	 * 'size' is an array defining the size of the widget on the grid:
	 *   'size' => [
	 *      'min'     => (array) minimum size of the widget on the grid
	 *                      example: ['width' => 2, 'height' => 1]
	 * 	    'default' => (array) default size of the widget on the grid
	 * 	    'max'     => (array) maximum size of the widget on the grid
	 * 	  ]
	 *
	 * 'menu' is a list containing as many array needed for the widget.
	 * Each entry in the list represent an entry in the menu of the widget:
	 *    'menu' => [
	 * 	     [
	 *          'icon'     => (string) class of the icon displayed in front
	 *                                 of the entry
	 * 	        'text'     => (string) text displayed in the menu
	 *          'function' => (string) JavaScript function to be called when
	 *                          the entry in the menu is selected by the user
	 * 	     ]
	 * 	  ]
	 *
	 * 'jobs' is a list containing delayed/cycling javascript jobs:
	 *    'jobs' => [
	 * 	     [
	 * 	        'function' => (string) JavaScript function to be called
	 * 	        'delay'    => (int) delay in seconds between each execution of
	 *                        the JS function
	 * 	     ]
	 * 	  ]
	 *
	 * 'push' contains the JS function to be called on push event:
	 * 	  'push' => (string) JavaScript function to be called on push event
	 *
	 * @since 15.0.0
	 *
	 * @return array
	 */
	public function widgetSetup(): array;

	/**
	 * method executed when the widget call the net.requestWidget() from
	 * the Javascript API.
	 *
	 * @since 15.0.0
	 *
	 * @param IWidgetRequest $request
	 */
	public function requestWidget(IWidgetRequest $request);

}

