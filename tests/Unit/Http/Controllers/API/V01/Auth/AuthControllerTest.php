<?php

namespace Tests\Unit\Http\Controllers\API\V01\Auth;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

//Do not consider this data as the main database data.
    use RefreshDatabase;

    /*
     * Test register
     */

    public function test_register_should_be_validate()
    {
        $response = $this->postJson(route('auth.register'));
        $response->assertStatus(422);
    }

    public function test_new_user_can_register()
    {
        $response = $this->postJson(route('auth.register'), [
            'name' => 'Seyed Alireza Kahduyi',
            'email' => 'alireza.kahdooee@hotmail.com',
            'password' => '12345678',
        ]);
        $response->assertStatus(201);
    }

    /*
     * Test login
     */

    public function test_login_should_be_login()
    {
        $response = $this->postJson(route('auth.login'));
        $response->assertStatus(422);
    }

    public function test_user_can_login_with_true_credentials()
    {
        $user = User::factory(['email' => 'a@a.com'])->create();
        $response = $this->post(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }


    public function test_show_user_info_if_logged_in()
    {
        $user = User::factory(['email' => 'a@a.com'])->create();
        $response = $this->actingAs($user)->get(route('auth.user'));
        $response->assertStatus(200);
    }
    /*
     * Test logout
     */
    public function test_logged_in_user_can_logout()
    {
        $user = User::factory(['email' => 'a@a.com'])->create();
        $response = $this->actingAs($user)->postJson(route('auth.logout'));
        $response->assertStatus(200);
    }
}
