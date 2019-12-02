<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Library\BaseClass;
use App\Http\Controllers;
use App\Http\Controllers\CRUDController;

use DB;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use App\Http\Requests\UpdatePost;



class ButtonControllerTest extends TestCase
{
    public function testController_ButtonController()
    {

        $response = $this->json('get', '/all');
        $response->assertStatus(200);

        $response = $this->json('get', '/department1');
        $response->assertStatus(200);

        $response = $this->json('get', '/department2');
        $response->assertStatus(200);

        $response = $this->json('get', '/department3');
        $response->assertStatus(200);

        $response = $this->json('get', '/department4');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2007');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2014');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2015');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2016');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2017');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2018');
        $response->assertStatus(200);

        $response = $this->json('get', '/nyushabi2019');
        $response->assertStatus(200);

        // $response = $this->json('get', '/nyushabi2020');
        // $response->assertStatus(200);

        $response = $this->json('get', '/age20');
        $response->assertStatus(200);

        $response = $this->json('get', '/age30');
        $response->assertStatus(200);

        $response = $this->json('get', '/age40');
        $response->assertStatus(200);

        $response = $this->json('get', '/age50');
        $response->assertStatus(200);

        $response = $this->json('get', '/age60');
        $response->assertStatus(200);

        $response = $this->json('get', '/age_other');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month01');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month02');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month03');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month04');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month05');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month06');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month07');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month08');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month09');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month10');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month11');
        $response->assertStatus(200);

        $response = $this->json('get', '/kijun_month12');
        $response->assertStatus(200);

        $response = $this->json('get', '/retirement');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2016');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2017');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2018');
        $response->assertStatus(200);

        $response = $this->json('get', '/taishokubi2019');
        $response->assertStatus(200);

        $response = $this->json('get', '/all_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/department_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/gender_avg');
        $response->assertStatus(200);

        $response = $this->json('get', '/all_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/department_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/gender_count');
        $response->assertStatus(200);

        $response = $this->json('get', '/age_count');
        $response->assertStatus(200);

        var_dump('ButtonControllerTest END');
    }

    
}
