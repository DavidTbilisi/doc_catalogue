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
// use Illuminate\Foundation\Testing\DatabaseTransactions;

class IoTypeTest extends TestCase
{
    // use DatabaseTransactions;
    use RefreshDatabase;

    protected $user;

    public function setUp(): void{
        parent::setUp();

        // group > user > type table > io_object > translation from both
        Group::factory()->create(["id" => 3,]);
        $this->user = User::factory()->create(["id" => 1,]);

        Io_type::factory()->create([
            "id" => 1,
            "table" => "fonds"
        ]);
        
        Io_types_translation::factory()->create([
            "io_type_id"=>1, // Dependes on io_types table
            "fields"=>'{"name":"სახელი"}',
        ]);

        
        // Log::channel('app')->info("test groups", ['groups'=> $res] );
    }

    public function test_types_add()
    {

        // PARAMS: 
        // Type table
        // Tech column names
        // Translation column names


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

        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
            "table"=>"fonds",
            "cols" => [
                "a",
                "b",
                "c",
                "d",
            ],
            'names' => [
                'ა',
                'ბ',
                'გ',
                'დ',
            ]
        ]);

        // Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_FOUND);
    }

    public function test_types_field_rename_duplicated_columns()
    {

        // TODO:1062 Duplicate entry '3' for key 'PRIMARY' (SQL: insert into `groups`

        $response = $this->actingAs($this->user, 'web')->post('/admin/types/column',[
            "table"=>"test",
            "cols" => [
                "a,d",
                "b,a",
                "c,v",
                "d,i",
            ],
            'names' => [
                'ა',
                'ბ',
                'გ',
                'დ',
            ]
        ]);
        // /App/Http/Controllers/Administration/IoTypesController.php
        // Log::channel("app")->debug("Response", ["Response"=>$response]);
        $response->assertStatus(Code::HTTP_INTERNAL_SERVER_ERROR);

    }


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
    //     // $user = User::factory()->create();

    //     $response = $this->actingAs($this->user, 'web')->get('/admin/types/show/fonds');
    //     $response->assertOk();
    // }

}
