<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
class AuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


    public function test_register()
{
   $response = $this->postJson('/api/register', [
    'name' => 'joudi',
    'email' => 'joudi@gmail.com',
    'password' => Hash::make('123456'),
    'blood_type' => 'A+',
    'gender' => 1,
    'is_approved' => 0,
    'is_admin' => 0,
   ]);

   $response->assertStatus(201);
}
}
