<?php

namespace Tests\Feature;

use App\Models\Group;
use App\Models\Io;
use App\Models\Io_type;
use Tests\TestCase;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response as Code;
use Illuminate\Foundation\Testing\RefreshDatabase;



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
