<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response as Code;
use Tests\TestCase;

class IoTypeTest extends TestCase
{
    protected $user;

    public function setUp(): void{
        parent::setUp();
        $this->user = User::find(1);
    }



    public function test_types_add()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/types/add',[
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
        // http://localhost:8000/admin/types/delete/1
        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a",
                "b",
                "c",
                "d",
            ]
    ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }

    public function test_types_field_rename()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
            "table"=>"test",
    ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }


    public function test_types_field_delete()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
            "table"=>"test",
    ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }




    

    // Remove test table
    public function test_types_delete()
    {
        // http://localhost:8000/admin/types/delete/1
        $response = $this->actingAs($this->user, 'web')->post('/admin/types/delete/2',[
            "table"=>"test",
    ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }



    // OPENS

    public function test_types_opens()
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/types');
        $response->assertOk();
    }


    public function test_types_fonds_opens()
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/types/show/fonds');
        $response->assertOk();
    }

}
