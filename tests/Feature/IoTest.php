<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response as Code;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IoTest extends TestCase
{
    public function setUp(): void{
        parent::setUp();
        $this->user = User::find(1);
    }


    public function test_io_create()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
            "prefix"=>"prefix",
            "identifier"=>"123",
            "suffix"=>"suffix", 
            "io_type_id"=>"1", // table id
            "data_id"=>"1", 
   
        ]);
        $response->assertStatus(Code::HTTP_CREATED);
    }

    public function test_io_connected_table_adds_info()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
            "prefix"=>"prefix",
            "name"=>"123",
            "suffix"=>"suffix", 
            "table"=>"fonds",
        ]);
        $response->assertStatus(Code::HTTP_CREATED);
    }



    // OPENS PAGES

    public function test_io_add_opens()
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/io/add');
        $response->assertOk();
    }

    public function test_io_opens()
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/io');
        $response->assertOk();
    }


    public function test_io_edit_opens()
    {
        $response = $this->actingAs($this->user, 'web')->get('/admin/io/edit/1');
        $response->assertOk();
    }

   


}
