<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // This will run before each test
    $this->seed();
});

test('can list all customers', function () {
    $response = $this->getJson('/api/customers');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name', 
                    'surname',
                    'email',
                    'created_at',
                    'updated_at',
                    'orders'
                ]
            ],
            'links'
        ]);
});

test('can show a specific customer', function () {
    $customer = Customer::first();
    
    $response = $this->getJson("/api/customers/{$customer->id}");

    $response->assertStatus(200)
        ->assertJsonStructure([
            'id',
            'name',
            'surname', 
            'email',
            'created_at',
            'updated_at',
            'orders' => [
                '*' => [
                    'id',
                    'customer_id',
                    'created_at',
                    'updated_at',
                    'items'
                ]
            ]
        ]);
});

test('can create a customer', function () {
    $customerData = [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => 'john.doe@example.com'
    ];

    $response = $this->postJson('/api/customers', $customerData);

    $response->assertStatus(201)
        ->assertJsonFragment($customerData);

    $this->assertDatabaseHas('customers', $customerData);
});

test('can update a customer', function () {
    $customer = Customer::first();
    $updateData = [
        'name' => 'Jane',
        'surname' => 'Smith',
        'email' => 'jane.smith@example.com'
    ];

    $response = $this->putJson("/api/customers/{$customer->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('customers', $updateData);
});

test('can partially update a customer', function () {
    $customer = Customer::first();
    $updateData = ['name' => 'UpdatedName'];

    $response = $this->putJson("/api/customers/{$customer->id}", $updateData);

    $response->assertStatus(200)
        ->assertJsonFragment($updateData);

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'UpdatedName'
    ]);
});

test('can delete a customer', function () {
    $customer = Customer::first();

    $response = $this->deleteJson("/api/customers/{$customer->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Customer deleted successfully']);

    $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
});

test('validates required fields when creating customer', function () {
    $response = $this->postJson('/api/customers', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'surname', 'email']);
});

test('validates email format when creating customer', function () {
    $response = $this->postJson('/api/customers', [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => 'invalid-email'
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('validates email uniqueness when creating customer', function () {
    $existingCustomer = Customer::first();
    
    $response = $this->postJson('/api/customers', [
        'name' => 'John',
        'surname' => 'Doe',
        'email' => $existingCustomer->email
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('returns 404 for non-existent customer', function () {
    $response = $this->getJson('/api/customers/999999');

    $response->assertStatus(404);
});