<?php

namespace Tests\Feature;

use App\Models\Io;
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Io_type;
use App\Models\Io_types_translation;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as Code;

class IoTypeTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void{
        parent::setUp();

// group > user > type table > io_object > translation from both


        Group::factory()->create(["id" => 3,]);
        $this->user = User::factory()->create(["id" => 1,]);

        Io_types_translation::factory()->create([
            "io_type_id"=>1,
            "fields"=>'{"string":"სტრიქონი"}',
        ]);

        Io_type::factory()->create([
            "id" => 1,
            "table" => "fonds"
        ]);

 
        
        // Log::channel('app')->info("test groups", ['groups'=> $res] );
    }

    // public function tearDown(): void 
    // {
    //     parent::tearDown();
    //     Group::trancate();

    // }

    public function test_types_add()
    {

        $response = $this->actingAs($this->user, 'web')->post('/admin/types/add',[
            "tablename"=>"test",
            "type"=>["string",],
            "fields"=>'{"string":"სტრიქონი"}',
            "field"=>["p2",],
        ]);

        Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }
    


    // FIELDS Manipulations

    public function test_types_field_add()
    {
        $user = User::factory()->create();

        Io_type::factory()->create([
            "tablename"=>"test",
            "type"=>["string",],
            "fields"=>'{"string":"სტრიქონი"}',
            "field"=>["p2",],
        ]);

        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
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

    // public function test_types_field_rename_duplicated_columns()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
    //         "table"=>"test",
    //         "cols" => [
    //             "a,d",
    //             "b,a",
    //             "c,v",
    //             "d,i",
    //         ]
    //     ]);
    //     // /App/Http/Controllers/Administration/IoTypesController.php
    //     // Log::channel("app")->debug("Response", ["Response"=>$response]);
    //     $response->assertStatus(Code::HTTP_INTERNAL_SERVER_ERROR);

    // }


    // public function test_types_field_rename()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
    //         "table"=>"test",
    //         "cols" => [
    //             "a,f",
    //             "b,e",
    //             "c,v",
    //             "d,i",
    //         ]
    //     ]);

    //     $response->assertStatus(Code::HTTP_FOUND);
    // }

    // public function test_types_field_delete()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
    //         "table"=>"test",
    //         "cols" => [
    //             "a",
    //             "b",
    //         ]
    //     ]);

    //     // Log::channel("app")->debug("Response", ["Response"=>$response]);
    //     $response->assertStatus(Code::HTTP_FOUND);
    // }



    // // Remove test table
    // public function test_types_delete()
    // {
    //     $user = User::factory()->create();

    //     // http://localhost:8000/admin/types/delete/1
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/types/delete/1',[
    //         "table"=>"test",
    // ]);
    //     $response->assertStatus(Code::HTTP_FOUND);
    // }


    // // OPENS

    // public function test_types_opens()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->get('/admin/types');
    //     $response->assertOk();
    // }


    // public function test_types_fonds_opens()
    // {
    //     $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->get('/admin/types/show/fonds');
    //     $response->assertOk();
    // }

}