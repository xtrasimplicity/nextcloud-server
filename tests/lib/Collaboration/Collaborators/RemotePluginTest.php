<?php
/**
 * @copyright Copyright (c) 2017 Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
 * @author Arthur Schiwon <blizzz@arthur-schiwon.de>
 *
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

namespace Test\Collaboration\Collaborators;


use OC\Collaboration\Collaborators\RemotePlugin;
use OC\Collaboration\Collaborators\SearchResult;
use OC\Federation\CloudIdManager;
use OCP\Collaboration\Collaborators\SearchResultType;
use OCP\Contacts\IManager;
use OCP\Federation\ICloudIdManager;
use OCP\IConfig;
use OCP\Share;
use Test\TestCase;

class RemotePluginTest extends TestCase {
	/** @var  IConfig|\PHPUnit_Framework_MockObject_MockObject */
	protected $config;

	/** @var  IManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $contactsManager;

	/** @var  ICloudIdManager|\PHPUnit_Framework_MockObject_MockObject */
	protected $cloudIdManager;

	/** @var  RemotePlugin */
	protected $plugin;

	/** @var  SearchResult */
	protected $searchResult;

	public function setUp() {
		parent::setUp();

		$this->config = $this->createMock(IConfig::class);
		$this->contactsManager = $this->createMock(IManager::class);
		$this->cloudIdManager = new CloudIdManager();
		$this->searchResult = new SearchResult();
	}

	public function instantiatePlugin() {
		$this->plugin = new RemotePlugin($this->contactsManager, $this->cloudIdManager, $this->config);
	}

	/**
	 * @dataProvider dataGetRemote
	 *
	 * @param string $searchTerm
	 * @param array $contacts
	 * @param bool $shareeEnumeration
	 * @param array $expected
	 * @param bool $exactIdMatch
	 * @param bool $reachedEnd
	 */
	public function testSearch($searchTerm, array $contacts, $shareeEnumeration, array $expected, $exactIdMatch, $reachedEnd) {
		$this->config->expects($this->any())
			->method('getAppValue')
			->willReturnCallback(
				function($appName, $key, $default)
				use ($shareeEnumeration)
				{
					if ($appName === 'core' && $key === 'shareapi_allow_share_dialog_user_enumeration') {
						return $shareeEnumeration ? 'yes' : 'no';
					}
					return $default;
				}
			);

		$this->instantiatePlugin();

		$this->contactsManager->expects($this->any())
			->method('search')
			->with($searchTerm, ['CLOUD', 'FN'])
			->willReturn($contacts);

		$moreResults = $this->plugin->search($searchTerm, 2, 0, $this->searchResult);
		$result = $this->searchResult->asArray();

		$this->assertSame($exactIdMatch, $this->searchResult->hasExactIdMatch(new SearchResultType('remotes')));
		$this->assertEquals($expected, $result);
		$this->assertSame($reachedEnd, $moreResults);
	}

	/**
	 * @dataProvider dataTestSplitUserRemote
	 *
	 * @param string $remote
	 * @param string $expectedUser
	 * @param string $expectedUrl
	 */
	public function testSplitUserRemote($remote, $expectedUser, $expectedUrl) {
		$this->instantiatePlugin();

		list($remoteUser, $remoteUrl) = $this->plugin->splitUserRemote($remote);
		$this->assertSame($expectedUser, $remoteUser);
		$this->assertSame($expectedUrl, $remoteUrl);
	}

	/**
	 * @dataProvider dataTestSplitUserRemoteError
	 *
	 * @param string $id
	 * @expectedException \Exception
	 */
	public function testSplitUserRemoteError($id) {
		$this->instantiatePlugin();
		$this->plugin->splitUserRemote($id);
	}

	public function dataGetRemote() {
		return [
			['test', [], true, ['remotes' => [], 'exact' => ['remotes' => []]], false, true],
			['test', [], false, ['remotes' => [], 'exact' => ['remotes' => []]], false, true],
			[
				'test@remote',
				[],
				true,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'test@remote', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'test@remote']]]]],
				false,
				true,
			],
			[
				'test@remote',
				[],
				false,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'test@remote', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'test@remote']]]]],
				false,
				true,
			],
			[
				'test',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				true,
				['remotes' => [['label' => 'User @ Localhost (username@localhost)', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'username@localhost', 'server' => 'localhost']]], 'exact' => ['remotes' => []]],
				false,
				true,
			],
			[
				'test',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				false,
				['remotes' => [], 'exact' => ['remotes' => []]],
				false,
				true,
			],
			[
				'test@remote',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				true,
				['remotes' => [['label' => 'User @ Localhost (username@localhost)', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'username@localhost', 'server' => 'localhost']]], 'exact' => ['remotes' => [['label' => 'test@remote', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'test@remote']]]]],
				false,
				true,
			],
			[
				'test@remote',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				false,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'test@remote', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'test@remote']]]]],
				false,
				true,
			],
			[
				'username@localhost',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				true,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'User @ Localhost (username@localhost)', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'username@localhost', 'server' => 'localhost']]]]],
				true,
				true,
			],
			[
				'username@localhost',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				false,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'User @ Localhost (username@localhost)', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'username@localhost', 'server' => 'localhost']]]]],
				true,
				true,
			],
			// contact with space
			[
				'user name@localhost',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User Name @ Localhost',
						'CLOUD' => [
							'user name@localhost',
						],
					],
				],
				false,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'User Name @ Localhost (user name@localhost)', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'user name@localhost', 'server' => 'localhost']]]]],
				true,
				true,
			],
			// remote with space, no contact
			[
				'user space@remote',
				[
					[
						'FN' => 'User3 @ Localhost',
					],
					[
						'FN' => 'User2 @ Localhost',
						'CLOUD' => [
						],
					],
					[
						'FN' => 'User @ Localhost',
						'CLOUD' => [
							'username@localhost',
						],
					],
				],
				false,
				['remotes' => [], 'exact' => ['remotes' => [['label' => 'user space@remote', 'value' => ['shareType' => Share::SHARE_TYPE_REMOTE, 'shareWith' => 'user space@remote']]]]],
				false,
				true,
			],
		];
	}

	public function dataTestSplitUserRemote() {
		$userPrefix = ['user@name', 'username'];
		$protocols = ['', 'http://', 'https://'];
		$remotes = [
			'localhost',
			'local.host',
			'dev.local.host',
			'dev.local.host/path',
			'dev.local.host/at@inpath',
			'127.0.0.1',
			'::1',
			'::192.0.2.128',
			'::192.0.2.128/at@inpath',
		];

		$testCases = [];
		foreach ($userPrefix as $user) {
			foreach ($remotes as $remote) {
				foreach ($protocols as $protocol) {
					$baseUrl = $user . '@' . $protocol . $remote;

					$testCases[] = [$baseUrl, $user, $protocol . $remote];
					$testCases[] = [$baseUrl . '/', $user, $protocol . $remote];
					$testCases[] = [$baseUrl . '/index.php', $user, $protocol . $remote];
					$testCases[] = [$baseUrl . '/index.php/s/token', $user, $protocol . $remote];
				}
			}
		}
		return $testCases;
	}

	public function dataTestSplitUserRemoteError() {
		return array(
			// Invalid path
			array('user@'),

			// Invalid user
			array('@server'),
			array('us/er@server'),
			array('us:er@server'),

			// Invalid splitting
			array('user'),
			array(''),
			array('us/erserver'),
			array('us:erserver'),
		);
	}
}
