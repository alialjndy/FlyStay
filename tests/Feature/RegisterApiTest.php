<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Testing\Fakes\Fake;
use Tests\TestCase;

class RegisterApiTest extends TestCase
{
    use WithFaker ;
    // use RefreshDatabase ;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register_succesfully(): void
    {
        $payload = [
            'name'=>$this->faker->name ,
            'email'=>$this->faker->unique()->safeEmail() ,
            'password' => 'newPassword123@' ,
            'password_confirmation' => 'newPassword123@',
            'phone_number' => $this->faker->numerify('09###########'),
        ];

        $response = $this->postJson('/api/register' , $payload);
        $response->assertStatus(201)->assertJsonStructure(['message','data' => ['token']]);
        $this->assertDatabaseHas('users',['email' => $payload['email']]);
    }
    public function test_register_fails_with_missing_fields(){
        $payload = [
            'name' => 'Ali'
        ];
        $response = $this->postJson('/api/register' , $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email' , 'password']);
    }
    public function test_register_fails_when_passwords_do_not_match(){
        $payload = [
            'name' => 'Ali123' ,
            'email' => 'Ali123@gmail.com' ,
            'password' => 'newpassword' ,
            'password_confirmation' => 'different_password'
        ];
        $response = $this->postJson('/api/register' , $payload);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }
}
