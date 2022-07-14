<?php

namespace AliAlizade\Customer\Tests\Feature;

use AliAlizade\Customer\Models\Customer;
use AliAlizade\Customer\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class CustomerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_be_created()
    {
        $this->withoutExceptionHandling();

        $data = [
            'name'                   => 'Ali Alizade',
            'initial_deposit_amount' => 200.99,
            'currency'               => 'USD',
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json(
            'post',
            '/api/v1/customers',
            $data
        );

        $customer = Customer::query()->firstWhere(['name' => $data['name']]);

        $this->assertDatabaseHas('customers', ['name' => $customer->name]);
        $this->assertDatabaseHas('accounts', [
            'customer_id'    => $customer->id,
            'currency'       => $data['currency'],
            'current_amount' => $data['initial_deposit_amount'],
        ]);


        $response->assertStatus(201)
                 ->assertJson(function (AssertableJson $json) use ($data) {
                     return $json->hasAll('status', 'data.customer',
                         'data.customer.account.account_number')
                                 ->where('data.customer.name', $data['name'])
                                 ->where('data.customer.account.currency', $data['currency'])
                                 ->where('data.customer.account.current_amount',
                                     $data['initial_deposit_amount'])
                                 ->etc();
                 });
    }
}