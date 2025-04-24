<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if a user can register successfully.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $userData = [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => '123456',
        ];

        $response = $this->postJson('/api/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'status',
                'message',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'authorization' => [
                    'token',
                    'type'
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'User created successfully',
                'user' => [
                    'name' => $userData['name'],
                    'email' => $userData['email']
                ],
                'authorization' => [
                    'type' => 'bearer'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        $user = User::where('email', $userData['email'])->first();
        $this->assertTrue(Hash::check('123456', $user->password));
    }

    /**
     * Test if a user can login successfully.
     *
     * @return void
     */
    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'password' => Hash::make('123456')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'authorization' => [
                    'token',
                    'type'
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'authorization' => [
                    'type' => 'bearer'
                ]
            ]);
    }

    /**
     * Test if a user can logout successfully.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->deleteJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Successfully logged out'
            ]);
    }

    /**
     * Test if a user can refresh their token successfully.
     *
     * @return void
     */
    public function test_user_can_refresh_token()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->postJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ],
                'authorization' => [
                    'token',
                    'type'
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'authorization' => [
                    'type' => 'bearer'
                ]
            ]);
    }

    /**
     * Test if a user can access their profile.
     *
     * @return void
     */
    public function test_user_can_access_profile()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->getJson('/api/me');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                    'updated_at'
                ]
            ])
            ->assertJson([
                'status' => 'success',
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email
                ]
            ]);
    }
}
