<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_customers()
    {
        Customer::factory()->count(3)->create();
        $response = $this->getJson('/api/customers');
        $response->assertStatus(200)->assertJsonCount(3, 'data');
    }

    public function test_can_create_customer()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
        ];
        $response = $this->postJson('/api/customers', $data);
        $response->assertStatus(201)->assertJsonFragment($data);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_can_show_customer()
    {
        $customer = Customer::factory()->create();
        $response = $this->getJson('/api/customers/' . $customer->id);
        $response->assertStatus(200)->assertJsonFragment([
            'id' => $customer->id,
            'email' => $customer->email,
        ]);
    }

    public function test_can_update_customer()
    {
        $customer = Customer::factory()->create();
        $data = ['name' => 'Updated', 'surname' => 'Name', 'email' => 'updated@example.com'];
        $response = $this->putJson('/api/customers/' . $customer->id, $data);
        $response->assertStatus(200)->assertJsonFragment($data);
        $this->assertDatabaseHas('customers', $data);
    }

    public function test_can_delete_customer()
    {
        $customer = Customer::factory()->create();
        $response = $this->deleteJson('/api/customers/' . $customer->id);
        $response->assertStatus(204);
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
