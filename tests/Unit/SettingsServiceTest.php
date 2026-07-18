<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\Settings\InterfaceTabHandler;
use App\Services\SettingsService;
use PHPUnit\Framework\TestCase;

class SettingsServiceTest extends TestCase
{
    /** @test */
    public function test_it_correctly_routes_to_handler_and_returns_tab_data(): void
    {
        $user = new User();

        $handlerMock = $this->createMock(InterfaceTabHandler::class);
        $handlerMock->expects($this->once())
            ->method('handleShow')
            ->with($user)
            ->willReturn([]);

        $service = $this->getMockBuilder(SettingsService::class)
            ->onlyMethods(['getHandler'])
            ->getMock();

        $service->method('getHandler')
            ->with('interface')
            ->willReturn($handlerMock);

        $result = $service->getTabData($user, 'interface');
        $this->assertEquals([], $result);
    }
}
