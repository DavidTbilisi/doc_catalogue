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



class IoTest extends TestCase
{

    use RefreshDatabase;

    protected $group;
    protected $user;

    public function setUp(): void{
        parent::setUp();

        Group::factory()->create(["id" => 3,]);

        $this->user = User::factory()->create(["id" => 1,]);

        Io_type::factory()->create([
            "id" => 1,
            "table" => "fonds"
        ]);

        Io::factory()->create(['id' => 1]);

        Io_types_translation::factory()->create([
            "io_type_id"=>1,
            "fields"=>'{"string":"სტრიქონი"}',
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
