<?php

namespace Tests\Feature;

use App\Models\Io;
use Tests\TestCase;
use App\Models\User;
use App\Models\Group;
use App\Models\Io_type;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as Code;


class IoEditTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void{
        parent::setUp();

        Group::factory()->create(["id" => 3,]);

        $this->user = User::factory()->create(["id" => 1,]);

        Io_type::factory()->create([
            "id" => 1,
            "table" => "fonds"
        ]);

        Io::factory()->create(['id' => 1]);
        
        // Log::channel('app')->info("test groups", ['groups'=> $res] );
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
 
 
     public function test_edit_io_no_pref_suff_identifier(){
         $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
             "prefix" => "",
             "identifier" => "",
             "suffix" => "",
             "io_type_id" => "1",
             "id" => 1
         ]);
         $response->assertStatus(Code::HTTP_FOUND);
     }
 
     public function test_edit_io(){
         $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/1',[
             "prefix" => "Prefix",
             "identifier" => "987",
             "suffix" => "suffix",
             "io_type_id" => "1",
             "id" => 1
         ]);
         $response->assertStatus(Code::HTTP_FOUND);
     }
 
 
 
     public function test_edit_io_no_id(){
         $response = $this->actingAs($this->user, 'web')->post('/admin/io/edit/',[
             "prefix" => "Prefix",
             "identifier" => "987",
             "suffix" => "suffix",
             "io_type_id" => "1",
         ]);
         $response->assertStatus(Code::HTTP_INTERNAL_SERVER_ERROR);
     }
 
 

     
 

}
