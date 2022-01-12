<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Io;
use App\Models\Io_type;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\Response as Code;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\map;

class IoTest extends TestCase
{

    use RefreshDatabase;

    protected $group;
    protected $user;

    public function setUp(): void{
        parent::setUp();

        // $groups = Group::factory()->count(3)->create();

        Group::factory()->create([
            "id" => 3,
        ]);

        $this->user = User::factory()->create([
            "id" => 1,
        ]);

        Io_type::factory()->create([
            "id" => 1,
            "table" => "fonds"
        ]);

        Io::factory()->create([
            'id' => 1
        ]);
        
        // Log::channel('app')->info("test groups", ['groups'=> $res] );
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


    public function test_io_connected_table_adds_info()
    {

        $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
            "name"=>"ფონდის სახელი",
            "table"=>"fonds",
        ]);
        $response->assertStatus(Code::HTTP_CREATED);
    }


    public function test_io_connected_table_bad_adds_info()
    {
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/add',[
            "name"=>"",
            "table"=>"fonds",
        ]);
        $response->assertStatus(Code::HTTP_BAD_REQUEST);
    }


    // EDITING Fields Os
    public function test_edit_io_no_prefix(){
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
            "prefix" => "",
            "identifier" => "987",
            "suffix" => "suffix",
            "io_type_id" => "1",
            "id" => 1
        ]);

        
        $response->assertStatus(Code::HTTP_FOUND);
    }

    public function test_edit_io_no_suffix(){
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
            "prefix" => "Prefix",
            "identifier" => "987",
            "suffix" => "",
            "io_type_id" => "1",
            "id" => 1
        ]);
        $response->assertStatus(Code::HTTP_FOUND);
    }


    public function test_edit_io_no_pref_suff(){
        $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
            "prefix" => "",
            "identifier" => "987",
            "suffix" => "",
            "io_type_id" => "1",
            "id" => 1
        ]);
        $response->assertStatus(Code::HTTP_FOUND);
    }


    // public function test_edit_io_no_pref_suff_identifier(){
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
    //         "prefix" => "",
    //         "identifier" => "",
    //         "suffix" => "",
    //         "io_type_id" => "1",
    //         "id" => 1
    //     ]);
    //     $response->assertStatus(Code::HTTP_FOUND);
    // }

    // public function test_edit_io(){
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
    //         "prefix" => "Prefix",
    //         "identifier" => "987",
    //         "suffix" => "suffix",
    //         "io_type_id" => "1",
    //         "id" => 1
    //     ]);
    //     $response->assertStatus(Code::HTTP_FOUND);
    // }



    // public function test_edit_io_no_id(){
    //     $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/',[
    //         "prefix" => "Prefix",
    //         "identifier" => "987",
    //         "suffix" => "suffix",
    //         "io_type_id" => "1",
    //     ]);
    //     $response->assertStatus(Code::HTTP_INTERNAL_SERVER_ERROR);
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
