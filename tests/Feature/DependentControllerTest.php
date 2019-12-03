<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DependentControllerTest extends TestCase
{

    public function testController_DependentcontrollerTest()
    {
        $response = $this->json('post', '/dependent_info/202020');
        $response->assertSee('扶養家族明細');
        $response->assertSee('テストデータ15（妻）');
        $response->assertSee('テストデータ15（子）');
        $response->assertStatus(200);
    }
}
