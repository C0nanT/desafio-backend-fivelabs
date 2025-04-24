<?php

namespace Tests\Feature;

use App\Models\Tasks;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;
    
    protected $user;
    protected $adminUser;
    protected $token;
    protected $adminToken;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'is_admin' => false
        ]);
        
        $this->adminUser = User::factory()->create([
            'is_admin' => true
        ]);
        
        $this->token = auth()->guard('api')->login($this->user);
        $this->adminToken = auth()->guard('api')->login($this->adminUser);
    }
}