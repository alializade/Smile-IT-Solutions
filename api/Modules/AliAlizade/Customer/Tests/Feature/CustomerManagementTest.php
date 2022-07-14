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

        $customer = Customer::firstWhere(['name' => $data['name']]);

        $this->assertDatabaseHas('customers', ['name' => $customer->name]);
        $this->assertDatabaseHas('accounts', [
            'customer_id'    => $customer->id,
            'currency'       => $data['currency'],
            'current_amount' => $data['initial_deposit_amount'],
        ]);


        $response->assertStatus(201)
                 ->assertJson(function (AssertableJson $json) use ($data) {
                     return $json->hasAll('status', 'data.customer',
                         'data.account.account_number')
                                 ->where('data.customer.name', $data['name'])
                                 ->where('data.account.currency', $data['currency'])
                                 ->where('data.account.current_amount',
                                     $data['initial_deposit_amount'])
                                 ->etc();
                 });
    }

    public function test_name_is_required()
    {
        $this->assertValidationOf(
            'name',
            '/api/v1/customers',
            [
                'name'                   => '',
                'initial_deposit_amount' => 200.99,
                'currency'               => 'USD',
            ],
            'required'
        );
    }

    public function test_name_at_least_has_three_characters()
    {
        $this->assertValidationOf(
            'name',
            '/api/v1/customers',
            [
                'name'                   => 'Al',
                'initial_deposit_amount' => 200.99,
                'currency'               => 'USD',
            ],
            'must be at least 3 characters.'
        );
    }

    public function test_initial_deposit_amount_is_required()
    {
        $this->assertValidationOf(
            'initial_deposit_amount',
            '/api/v1/customers',
            [
                'name'                   => 'Ali Alizade',
                'initial_deposit_amount' => '',
                'currency'               => 'USD',
            ],
            'required'
        );
    }

    public function test_initial_deposit_amount_is_numeric()
    {
        $this->assertValidationOf(
            'initial_deposit_amount',
            '/api/v1/customers',
            [
                'name'                   => 'Ali Alizade',
                'initial_deposit_amount' => 'letters',
                'currency'               => 'USD',
            ],
            'must be a number.'
        );
    }

    public function test_the_minimum_value_of_initial_deposit_amount_is_15()
    {
        $this->assertValidationOf(
            'initial_deposit_amount',
            '/api/v1/customers',
            [
                'name'                   => 'Ali Alizade',
                'initial_deposit_amount' => 14,
                'currency'               => 'USD',
            ],
            'must be at least 15.'
        );
    }

    public function test_currency_is_required()
    {
        $this->assertValidationOf(
            'currency',
            '/api/v1/customers',
            [
                'name'                   => 'Ali Alizade',
                'initial_deposit_amount' => 200.99,
                'currency'               => '',
            ],
            'required'
        );
    }

    public function test_currency_must_be_valid()
    {
        $this->assertValidationOf(
            'currency',
            '/api/v1/customers',
            [
                'name'                   => 'Ali Alizade',
                'initial_deposit_amount' => 200.99,
                'currency'               => 'WRONG',
            ],
            'is invalid.'
        );
    }
}