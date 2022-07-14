<?php

namespace AliAlizade\Customer\Tests\Feature;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use AliAlizade\Customer\Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class CustomerBankAccountsTest extends TestCase
{
    use RefreshDatabase;

    public function test_balance_of_an_account_can_be_seen()
    {
        $this->withoutExceptionHandling();

        $account = Account::factory()
                          ->set('current_amount', 20)
                          ->create();

        $response = $this->json(
            'get',
            sprintf("/api/v1/accounts/%s", $account->account_number)
        );

        $response->assertStatus(200)
                 ->assertExactJson([
                     'status' => 'Ok',
                     'data'   => [
                         'account' => [
                             'account_number' => $account->account_number,
                             'currency'       => $account->currency,
                             'customer'       => [
                                 'id'   => $account->customer->id,
                                 'name' => $account->customer->name,
                             ],
                             'current_amount' => 20,
                         ],
                     ],
                 ]);
    }

    public function test_account_balances_of_a_customer_can_be_seen()
    {
        $this->withoutExceptionHandling();

        $customer = Customer::factory()->create();

        $accounts = Account::factory()
                           ->count(4)
                           ->set('customer_id', $customer->id)
                           ->state(new Sequence(
                               ['current_amount' => 0],
                               ['current_amount' => 10],
                               ['current_amount' => 20],
                               ['current_amount' => 30],
                               ['current_amount' => 50],
                           ))->create();

        $response = $this->json(
            'get',
            sprintf("/api/v1/customers/%s/accounts", $customer->id)
        );

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'data' => [
                         'total_balance',
                         'accounts',
                     ],
                 ]);
    }

    public function test_an_existed_customer_can_create_new_bank_accounts()
    {
        $this->withoutExceptionHandling();

        $customer = Customer::factory()->create();

        $data = [
            'initial_deposit_amount' => 150,
            'currency'               => 'USD',
            'customer_id'            => $customer->id,
        ];

        $response = $this->json(
            'post',
            '/api/v1/accounts',
            $data
        );

        $this->assertDatabaseHas('accounts', [
            'customer_id'    => $customer->id,
            'current_amount' => $data['initial_deposit_amount'],
            'currency'       => $data['currency'],
        ]);


        $response->assertStatus(201)
                 ->assertJson(function (AssertableJson $json) use ($data, $customer) {
                     return $json->hasAll('status', 'data.customer',
                         'data.account.account_number')
                                 ->where('data.customer.name', $customer->name)
                                 ->where('data.account.currency', $data['currency'])
                                 ->where('data.account.current_amount',
                                     $data['initial_deposit_amount'])
                                 ->etc();
                 });
    }

}