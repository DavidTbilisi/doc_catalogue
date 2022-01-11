<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as Code;
use Tests\TestCase;

class IoTypeTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();
        // Artisan::call("migrate:fresh");
        // Artisan::call("db:seed");
        // $user = User::find(1);
    }



    public function test_types_add()
    {

        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post('/admin/types/add',[
            "name"=>"ტესტი",
            "tablename"=>"test",
            "type"=>[
                "string",
            ],
            "field"=>[
                "p2",
            ],
        ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }
    


    // FIELDS Manipulations

    public function test_types_field_add()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a",
                "b",
                "c",
                "d",
            ]
        ]);

        // Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }

    public function test_types_field_rename_duplicated_columns()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a,d",
                "b,a",
                "c,v",
                "d,i",
            ]
        ]);

        // Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_INTERNAL_SERVER_ERROR);

    }


    public function test_types_field_rename()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a,f",
                "b,e",
                "c,v",
                "d,i",
            ]
        ]);

        $response->assertStatus(Code::HTTP_FOUND);
    }

    public function test_types_field_delete()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a",
                "b",
            ]
        ]);

        // Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }



    // Remove test table
    public function test_types_delete()
    {
        $user = User::factory()->create();

        // http://localhost:8000/admin/types/delete/1
        $response = $this->actingAs($user, 'web')->post('/admin/types/delete/2',[
            "table"=>"test",
    ]);
        $response->assertStatus(Code::HTTP_FOUND);
    }


    // OPENS

    public function test_types_opens()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get('/admin/types');
        $response->assertOk();
    }


    public function test_types_fonds_opens()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'web')->get('/admin/types/show/fonds');
        $response->assertOk();
    }

}
