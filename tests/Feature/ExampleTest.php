<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_guest_can_open_customer_registration_form(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Daftar Customer');
    }

    public function test_customer_can_open_create_form_but_not_admin_routes(): void
    {
        $customer = new User([
            'name' => 'Customer',
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $this->actingAs($customer)
            ->get(route('pengaduan.create'))
            ->assertOk();

        $this->get('/')
            ->assertRedirect(route('dashboard.customer'));

        $this->actingAs($customer)
            ->get(route('dashboard.admin'))
            ->assertForbidden();

        $this->actingAs($customer)
            ->get(route('pengaduan.index'))
            ->assertForbidden();
    }

    public function test_admin_cannot_open_customer_create_form(): void
    {
        $admin = new User([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);

        $this->actingAs($admin)
            ->get(route('pengaduan.create'))
            ->assertForbidden();

        $this->get('/')
            ->assertRedirect(route('dashboard.admin'));
    }
}
