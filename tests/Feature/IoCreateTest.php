<?php

namespace Tests\Feature;

use App\Models\Io;
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Io_type;
use App\Models\Io_types_translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as Code;


class IoCreateTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();
        sleep(0.5);
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
        Io::factory()->create(['id' => 1]);
    }


    // CREATING IOs
    public function test_io_create()
    {

        // IoController
        // TODO: თუ შესაძლებელია კარგი ინქებოდა ორი მოთხოვნის გაერთიანება. 
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
            "prefix"=>"prefix",
            "identifier"=>"123",
            "suffix"=>"suffix", 
            "io_type_id"=>"1", // table id
            "data_id"=>"1", 
   
        ]);
        $response->assertStatus(Code::HTTP_CREATED);
    }

     
    // public function test_io_connected_table_adds_info()
    // {
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
    //         "name"=>"ფონდის სახელი",
    //         "table"=>"fonds",
    //     ]);
    //     $response->assertStatus(Code::HTTP_CREATED);
    // }
 
    // public function test_io_connected_table_bad_adds_info()
    // {
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
    //         "name"=>"",
    //         "table"=>"fonds",
    //     ]);
    //     $response->assertStatus(Code::HTTP_BAD_REQUEST);
    // }

    // public function test_add_io_not_found_error() {
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/delete/2',[
    //         "prefix" => "Prefix",
    //         "identifier" => "987",
    //         "suffix" => "suffix",
    //         "io_type_id" => "1",
    //     ]);
    //     $response->assertStatus(Code::HTTP_NOT_FOUND);
    // }
    

    // public function test_add_io() {

    //     // add info to connected table
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/add/',[
    //         "table" => "fond",
    //         "name" => "something",
    //     ]);

    //     // if written, write to io table
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/add/',[
    //         "prefix" => "P",
    //         "identifier" => "987",
    //         "suffix" => "s",
    //         "io_type_id" => "1",
    //         "type" => "fond",
    //         "data_id" => "1",
      
    //     ]);
    //     $response->assertStatus(Code::HTTP_CREATED);
    // }
    

}
