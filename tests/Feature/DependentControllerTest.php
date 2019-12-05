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
        $response->assertStatus(200);

        $response = $this->json('post', '/dependent_info_edit/202020');
        $response->assertStatus(200);

        $id = '1';
        $this->json('patch', '/dependent_info_update/' . $id, [

            'shain_cd' => '202020',
            'name' => 'テストデータアップデート',
            'name_kana' => 'テストデータアップデート',
            'gender' => '女',
            'birthday' => '1999-01-01',
            'haigusha' => '1',
            'shikakushutokubi' => '1999-01-01',
        ]);

        $this->assertDatabaseHas('dependent_infos', ['shain_cd' => '202020']);
        $this->assertDatabaseHas('dependent_infos', ['name' => 'テストデータアップデート']);
        $this->assertDatabaseHas('dependent_infos', ['birthday' => '1999-01-01']);


        $id = '1';
        $this->json('patch', '/dependent_info_update/' . $id, [

            'shain_cd' => null,
            'name' => 'エラーテストデータアップデート',
            'name_kana' => 'エラーテストデータアップデート',
            'gender' => '女',
            'birthday' => '1989-01-01',
            'haigusha' => '1',
            'shikakushutokubi' => '1999-01-01',
        ]);

        $this->assertDatabaseMissing('dependent_infos', ['birthday' => '1989-01-01']);

        // 　挿入したデータをを削除する
        \DB::table('dependent_infos')
            ->where('name', 'テストデータアップデート')
            ->delete();

        \DB::table('dependent_infos')
            ->insert(
                [
                    'id' => 1,
                    'shain_cd' => '202020',
                    'name' => 'テストデータ（配偶者）',
                    'name_kana' => 'テストデータ（配偶者）',
                    'gender' => '男',
                    'birthday' => '2000-01-01',
                    'haigusha' => 1,
                    'kisonenkin_bango' => null,
                    'shikakushutokubi' => '2019-08-01',
                ]
            );


        $response = $this->json('post', '/dependent_info_add/202020');
        $response->assertStatus(200);

        $response = $this->json('post', '/dependent_info_submit/202020', [

            'shain_cd' => '202020',
            'name' => '扶養家族新規登録',
            'name_kana' => '扶養家族新規登録',
            'gender' => '男',
            'birthday' => '1900-01-01',
            'haigusha' => '0',
            'kisonenkin_bango' => '0000-000000',
            'shikakushutokubi' => '1900-02-01',
        ]);

        $this->assertDatabaseHas('dependent_infos', ['name' => '扶養家族新規登録']);
        $this->assertDatabaseHas('dependent_infos', ['birthday' => '1900-01-01']);

        $response = $this->json('post', '/dependent_info_submit/202020', [

            'shain_cd' => '202020',
            'name' => '扶養家族新規登録',
            'name_kana' => '扶養家族新規登録',
            'gender' => '男',
            'birthday' => '1900-01-01',
            'haigusha' => '0',
            'kisonenkin_bango' => '0000-000000',
            'shikakushutokubi' => '1900-02-01',
        ]);
        $response->assertStatus(200);

        $response = $this->json('post', '/dependent_info_submit/202020', [

            'shain_cd' => '202020',
            'name' => null,
            'name_kana' => '扶養家族新規登録',
            'gender' => '男',
            'birthday' => '1900-01-01',
            'haigusha' => '0',
            'kisonenkin_bango' => '0000-000000',
            'shikakushutokubi' => '1900-02-01',
        ]);
        $response->assertStatus(200);


         // 　挿入したデータをを削除する
         \DB::table('dependent_infos')
         ->where('name', '扶養家族新規登録')
         ->delete();

         \DB::table('dependent_infos')
            ->insert(
                                              [
                    'id' => 100,
                    'shain_cd' => '202020',
                    'name' => '削除テストデータ',
                    'name_kana' => '削除テストデータ',
                    'gender' => '男',
                    'birthday' => '1000-01-01',
                    'haigusha' => 1,
                    'kisonenkin_bango' => null,
                    'shikakushutokubi' => '1000-08-01',
                ]
            );

        $response = $this->json('post', '/dependent_info_delete/202020', [

            'id' => '100',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('dependent_infos', ['name' => '削除テストデータ']);

        \DB::table('dependent_infos')
            ->insert(
                                              [
                    'id' => 100,
                    'shain_cd' => '202020',
                    'name' => '削除テストデータ',
                    'name_kana' => '削除テストデータ',
                    'gender' => '男',
                    'birthday' => '1000-01-01',
                    'haigusha' => 1,
                    'kisonenkin_bango' => null,
                    'shikakushutokubi' => '1000-08-01',
                ]
            );

        $response = $this->json('post', '/dependent_info_delete_to_edit/202020', [

            'id' => '100',
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseMissing('dependent_infos', ['name' => '削除テストデータ']);
        
    }
}
