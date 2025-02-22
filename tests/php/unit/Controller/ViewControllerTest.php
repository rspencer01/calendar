<?php
declare(strict_types=1);
/**
 * Calendar App
 *
 * @author Georg Ehrke
 * @copyright 2019 Georg Ehrke <oc.list@georgehrke.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Calendar\Controller;

use OCP\App\IAppManager;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\IConfig;
use OCP\IRequest;
use ChristophWurst\Nextcloud\Testing\TestCase;

class ViewControllerTest extends TestCase {

	/** @var string */
	private $appName;

	/** @var IRequest|\PHPUnit_Framework_MockObject_MockObject */
	private $request;

	/** @var IAppManager|\PHPUnit_Framework_MockObject_MockObject */
	private $appManager;

	/** @var IConfig|\PHPUnit_Framework_MockObject_MockObject */
	private $config;

	/** @var string */
	private $userId;

	/** @var ViewController */
	private $controller;

	protected function setUp():void {
		$this->appName = 'calendar';
		$this->request = $this->createMock(IRequest::class);
		$this->appManager = $this->createMock(IAppManager::class);
		$this->config = $this->createMock(IConfig::class);
		$this->userId = 'user123';

		$this->controller = new ViewController($this->appName, $this->request,
			$this->config, $this->appManager, $this->userId);
	}

	public function testIndex():void {
		$this->config->expects($this->at(0))
			->method('getAppValue')
			->with('calendar', 'currentView', 'dayGridMonth')
			->willReturn('defaultCurrentView');
		$this->config->expects($this->at(1))
			->method('getAppValue')
			->with('calendar', 'showWeekends', 'yes')
			->willReturn('defaultShowWeekends');
		$this->config->expects($this->at(2))
			->method('getAppValue')
			->with('calendar', 'showWeekNr', 'no')
			->willReturn('defaultShowWeekNr');
		$this->config->expects($this->at(3))
			->method('getAppValue')
			->with('calendar', 'skipPopover', 'no')
			->willReturn('defaultSkipPopover');
		$this->config->expects($this->at(4))
			->method('getAppValue')
			->with('calendar', 'timezone', 'automatic')
			->willReturn('defaultTimezone');

		$this->config->expects($this->at(5))
			->method('getAppValue')
			->with('calendar', 'installed_version')
			->willReturn('1.0.0');
		$this->config->expects($this->at(6))
			->method('getUserValue')
			->with('user123', 'calendar', 'firstRun', 'yes')
			->willReturn('yes');
		$this->config->expects($this->at(7))
			->method('getUserValue')
			->with('user123', 'calendar', 'currentView', 'defaultCurrentView')
			->willReturn('timeGridWeek');
		$this->config->expects($this->at(8))
			->method('getUserValue')
			->with('user123', 'calendar', 'showWeekends', 'defaultShowWeekends')
			->willReturn('yes');
		$this->config->expects($this->at(9))
			->method('getUserValue')
			->with('user123', 'calendar', 'showWeekNr', 'defaultShowWeekNr')
			->willReturn('yes');
		$this->config->expects($this->at(10))
			->method('getUserValue')
			->with('user123', 'calendar', 'skipPopover', 'defaultSkipPopover')
			->willReturn('yes');
		$this->config->expects($this->at(11))
			->method('getUserValue')
			->with('user123', 'calendar', 'timezone', 'defaultTimezone')
			->willReturn('Europe/Berlin');
		$this->appManager->expects($this->at(0))
			->method('isEnabledForUser')
			->with('spreed')
			->willReturn(true);

		$response = $this->controller->index();

		$this->assertInstanceOf(TemplateResponse::class, $response);
		$this->assertEquals([
			'app_version' => '1.0.0',
			'first_run' => true,
			'initial_view' => 'timeGridWeek',
			'show_weekends' => true,
			'show_week_numbers' => true,
			'skip_popover' => true,
			'talk_enabled' => true,
			'timezone' => 'Europe/Berlin',
		], $response->getParams());
		$this->assertEquals('user', $response->getRenderAs());
		$this->assertEquals('main', $response->getTemplateName());
	}

	/**
	 * @dataProvider viewFixDataProvider
	 *
	 * @param string $savedView
	 * @param string $expectedView
	 */
	public function testIndexViewFix(string $savedView, string $expectedView):void {
		$this->config->expects($this->at(0))
			->method('getAppValue')
			->with('calendar', 'currentView', 'dayGridMonth')
			->willReturn('defaultCurrentView');
		$this->config->expects($this->at(1))
			->method('getAppValue')
			->with('calendar', 'showWeekends', 'yes')
			->willReturn('defaultShowWeekends');
		$this->config->expects($this->at(2))
			->method('getAppValue')
			->with('calendar', 'showWeekNr', 'no')
			->willReturn('defaultShowWeekNr');
		$this->config->expects($this->at(3))
			->method('getAppValue')
			->with('calendar', 'skipPopover', 'no')
			->willReturn('defaultSkipPopover');
		$this->config->expects($this->at(4))
			->method('getAppValue')
			->with('calendar', 'timezone', 'automatic')
			->willReturn('defaultTimezone');

		$this->config->expects($this->at(5))
			->method('getAppValue')
			->with('calendar', 'installed_version')
			->willReturn('1.0.0');
		$this->config->expects($this->at(6))
			->method('getUserValue')
			->with('user123', 'calendar', 'firstRun', 'yes')
			->willReturn('yes');
		$this->config->expects($this->at(7))
			->method('getUserValue')
			->with('user123', 'calendar', 'currentView', 'defaultCurrentView')
			->willReturn($savedView);
		$this->config->expects($this->at(8))
			->method('getUserValue')
			->with('user123', 'calendar', 'showWeekends', 'defaultShowWeekends')
			->willReturn('yes');
		$this->config->expects($this->at(9))
			->method('getUserValue')
			->with('user123', 'calendar', 'showWeekNr', 'defaultShowWeekNr')
			->willReturn('yes');
		$this->config->expects($this->at(10))
			->method('getUserValue')
			->with('user123', 'calendar', 'skipPopover', 'defaultSkipPopover')
			->willReturn('yes');
		$this->config->expects($this->at(11))
			->method('getUserValue')
			->with('user123', 'calendar', 'timezone', 'defaultTimezone')
			->willReturn('Europe/Berlin');
		$this->appManager->expects($this->at(0))
			->method('isEnabledForUser')
			->with('spreed')
			->willReturn(true);

		$response = $this->controller->index();

		$this->assertInstanceOf(TemplateResponse::class, $response);
		$this->assertEquals([
			'app_version' => '1.0.0',
			'first_run' => true,
			'initial_view' => $expectedView,
			'show_weekends' => true,
			'show_week_numbers' => true,
			'skip_popover' => true,
			'talk_enabled' => true,
			'timezone' => 'Europe/Berlin',
		], $response->getParams());
		$this->assertEquals('user', $response->getRenderAs());
		$this->assertEquals('main', $response->getTemplateName());
	}

	/**
	 * @return array
	 */
	public function viewFixDataProvider(): array {
		return [
			['agendaDay', 'timeGridDay'],
			['timeGridDay', 'timeGridDay'],
			['agendaWeek', 'timeGridWeek'],
			['timeGridWeek', 'timeGridWeek'],
			['month', 'dayGridMonth'],
			['dayGridMonth', 'dayGridMonth'],
		];
	}
}
