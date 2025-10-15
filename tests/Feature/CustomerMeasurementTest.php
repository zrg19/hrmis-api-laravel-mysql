<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CustomerMeasurement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class CustomerMeasurementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a test user
        $this->user = User::factory()->create([
            'role' => 'Admin',
            'is_active' => true
        ]);
        
        // Generate JWT token
        $this->token = JWTAuth::fromUser($this->user);
    }

    public function test_can_create_customer_measurement(): void
    {
        $data = [
            'name' => 'Faizan',
            'code' => '1122',
            'phone' => '03123456789',
            'address' => '123 Main Street',
            'kameezlength' => '42',
            'teera' => '24',
            'baazo' => '19',
            'chest' => '21',
            'neck' => '15',
            'daman' => '23',
            'kera' => 'gol',
            'shalwar' => '42',
            'pancha' => '12',
            'pocket' => '2',
            'note' => 'Stitching'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/customer-measurements', $data);

        $response->assertStatus(201)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'code',
                        'phone',
                        'address',
                        'kameezlength',
                        'teera',
                        'baazo',
                        'chest',
                        'neck',
                        'daman',
                        'kera',
                        'shalwar',
                        'pancha',
                        'pocket',
                        'note',
                        'created_by',
                        'updated_by',
                        'created_at',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('customer_measurements', [
            'name' => 'Faizan',
            'code' => '1122',
            'phone' => '03123456789'
        ]);
    }

    public function test_can_get_customer_measurements(): void
    {
        CustomerMeasurement::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/customer-measurements');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'name',
                            'code',
                            'phone',
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ]);
    }

    public function test_can_get_single_customer_measurement(): void
    {
        $customerMeasurement = CustomerMeasurement::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson("/api/customer-measurements/{$customerMeasurement->id}");

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        'id',
                        'name',
                        'code',
                        'phone',
                        'created_at',
                        'updated_at'
                    ]
                ]);
    }

    public function test_can_update_customer_measurement(): void
    {
        $customerMeasurement = CustomerMeasurement::factory()->create();

        $updateData = [
            'name' => 'Updated Name',
            'phone' => '03123456789'
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->putJson("/api/customer-measurements/{$customerMeasurement->id}", $updateData);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'data' => [
                        'id',
                        'name',
                        'code',
                        'phone',
                        'updated_at'
                    ]
                ]);

        $this->assertDatabaseHas('customer_measurements', [
            'id' => $customerMeasurement->id,
            'name' => 'Updated Name'
        ]);
    }

    public function test_can_soft_delete_customer_measurement(): void
    {
        $customerMeasurement = CustomerMeasurement::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->deleteJson("/api/customer-measurements/{$customerMeasurement->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('customer_measurements', [
            'id' => $customerMeasurement->id
        ]);
    }

    public function test_validation_requires_name_code_phone(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->postJson('/api/customer-measurements', []);

        $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'code', 'phone']);
    }

    public function test_can_search_customer_measurements(): void
    {
        CustomerMeasurement::factory()->create(['name' => 'John Doe']);
        CustomerMeasurement::factory()->create(['name' => 'Jane Smith']);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
            'Accept' => 'application/json'
        ])->getJson('/api/customer-measurements?search=John');

        $response->assertStatus(200);
        
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('John Doe', $data[0]['name']);
    }
}
