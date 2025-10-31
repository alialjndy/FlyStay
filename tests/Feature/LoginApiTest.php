<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginApiTest extends TestCase
{
    // use RefreshDatabase ;
    // protected function setUp(): void{
    //     parent::setUp();

    //     $this->artisan('db:seed', ['--class' => 'CountrySeeder']);
    //     $this->artisan('app:import-airports');
    //     $this->artisan('db:seed');
    // }
    public function test_login_succesffully(){
        $payload = [
            'email'=>'alialjndy2@gmail.com' ,
            'password' => 'strongPass123@'
        ];

        $resposne = $this->postJson('/api/login',$payload);
        $resposne->assertStatus(200)
            ->assertJsonStructure(['data' => ['token','roles']]);
    }

    public function test_failed_login_with_invalid_credentials(){
        $payload = [
            'email' => 'alialjndy2@gmail.com' ,
            'password' => 'wrongPassword'
        ];

        $response = $this->postJson('/api/login' , $payload);
        $response->assertStatus(400)
            ->assertJsonStructure(['errors']);
    }
}
