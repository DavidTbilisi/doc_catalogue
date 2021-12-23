<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IoTypeTest extends TestCase
{
    protected $user;

    public function setUp(): void{
        parent::setUp();
        $this->user = User::find(1);
    }

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
