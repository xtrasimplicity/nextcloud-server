<?php
declare(strict_types=1);


/**
 * Nextcloud - Dashboard app
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


namespace OC\Dashboard;


use Exception;
use OCP\Dashboard\Exceptions\DashboardAppNotAvailableException;
use OCP\App\IAppManager;
use OCP\Dashboard\IDashboardManager;
use OCP\Dashboard\Model\IWidgetSettings;
use OCP\IServerContainer;


/**
 * Class DashboardManager
 *
 * @package OC\Dashboard
 */
class DashboardManager implements IDashboardManager {


	/** @var IAppManager */
	private $appManager;

	/** @var IServerContainer */
	private $container;

	/**
	 * DashboardManager constructor.
	 *
	 * @param IAppManager $appManager
	 * @param IServerContainer $container
	 */
	public function __construct(IAppManager $appManager, IServerContainer $container) {
		$this->appManager = $appManager;
		$this->container = $container;
	}


	/**
	 * @param string $service
	 * @return mixed
	 * @throws DashboardAppNotAvailableException
	 */
	private function getService(string $service) {
		if (!$this->appManager->isInstalled('dashboard')) {
			throw new DashboardAppNotAvailableException('the dashboard app is not installed/available');
		}

		try {
			return $this->container->query($service);
		} catch (Exception $e) {
			throw new DashboardAppNotAvailableException('issue while querying ' . $service . ': ' . $e->getMessage());
		}
	}


	/**
	 * @param string $widgetId
	 * @param string $userId
	 *
	 * @return IWidgetSettings
	 * @throws DashboardAppNotAvailableException
	 */
	public function getWidgetSettings(string $widgetId, string $userId): IWidgetSettings {
		/** @var \OCA\Dashboard\Service\WidgetsService $widgetsService */
		$widgetsService = $this->getService('\OCA\Dashboard\Service\WidgetsService');
		return $widgetsService->getWidgetSettings($widgetId, $userId);
	}


	/**
	 * @param string $widgetId
	 * @param array $users
	 * @param array $payload
	 * @param string $uniqueId
	 *
	 * @throws DashboardAppNotAvailableException
	 */
	public function createUsersEvent(string $widgetId, array $users, array $payload, string $uniqueId = '') {
		/** @var \OCA\Dashboard\Service\EventsService $eventsService */
		$eventsService = $this->getService('\OCA\Dashboard\Service\EventsService');
		$eventsService->createUsersEvent($widgetId, $users, $payload, $uniqueId);
	}


	/**
	 * @param string $widgetId
	 * @param array $groups
	 * @param array $payload
	 * @param string $uniqueId
	 *
	 * @throws DashboardAppNotAvailableException
	 */
	public function createGroupsEvent(string $widgetId, array $groups, array $payload, string $uniqueId = '') {
		/** @var \OCA\Dashboard\Service\EventsService $eventsService */
		$eventsService = $this->getService('\OCA\Dashboard\Service\EventsService');
		$eventsService->createGroupsEvent($widgetId, $groups, $payload, $uniqueId);
	}


	/**
	 * @param string $widgetId
	 * @param array $payload
	 * @param string $uniqueId
	 *
	 * @throws DashboardAppNotAvailableException
	 */
	public function createGlobalEvent(string $widgetId, array $payload, string $uniqueId = '') {
		/** @var \OCA\Dashboard\Service\EventsService $eventsService */
		$eventsService = $this->getService('\OCA\Dashboard\Service\EventsService');
		$eventsService->createGlobalEvent($widgetId, $payload, $uniqueId);
	}

}

