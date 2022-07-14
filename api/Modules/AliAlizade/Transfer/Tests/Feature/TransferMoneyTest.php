<?php

namespace AliAlizade\Transfer\Tests\Feature;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use AliAlizade\Transfer\Enums\TransactionStatusEnum;
use AliAlizade\Transfer\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class TransferMoneyTest extends TestCase
{
    use RefreshDatabase;

    // todo: test if the user has enough balanced
    // todo: test if from and to accounts are the same
    // todo: test if currencies are the same
    public function test_money_can_be_transferred_between_two_customers()
    {
        /** @var Customer $customerA */
        /** @var Customer $customerB */

        $this->withoutExceptionHandling();

        $customerA = Customer::factory()
                             ->has(Account::factory()->set('current_amount', 100))
                             ->create();

        $customerB = Customer::factory()
                             ->has(Account::factory()->set('current_amount', 0))
                             ->create();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->json(
            'post',
            '/api/v1/transfer',
            [
                'from'   => $customerA->accounts[0]['account_number'],
                'to'     => $customerB->accounts[0]['account_number'],
                'amount' => 35,
            ],
        );

        $this->assertDatabaseHas('accounts', [
            'account_number' => $customerA->fresh('accounts')->accounts[0]['account_number'],
            'current_amount' => 65,
        ]);

        $this->assertDatabaseHas('accounts', [
            'account_number' => $customerB->fresh('accounts')->accounts[0]['account_number'],
            'current_amount' => 35,
        ]);

        $this->assertDatabaseHas('transactions', [
            'from'   => $customerA->accounts[0]['account_number'],
            'to'     => $customerB->accounts[0]['account_number'],
            'amount' => 35,
            'status' => TransactionStatusEnum::SUCCESS,
        ]);

        $response->assertStatus(200)
                 ->assertJson(function (AssertableJson $json) use ($customerA, $customerB) {
                     return $json->hasAll(
                         'status',
                         'data.transaction',
                         'data.transaction.created_at',
                         'data.transaction.tid',
                     )
                                 ->where('data.transaction.from',
                                     $customerA->accounts[0]['account_number'])
                                 ->where('data.transaction.to',
                                     $customerB->accounts[0]['account_number'])
                                 ->where('data.transaction.amount', 35)
                                 ->where('data.transaction.status',
                                     TransactionStatusEnum::SUCCESS->value)
                                 ->where('data.transaction.to',
                                     $customerB->accounts[0]['account_number']);
                 });
    }
}