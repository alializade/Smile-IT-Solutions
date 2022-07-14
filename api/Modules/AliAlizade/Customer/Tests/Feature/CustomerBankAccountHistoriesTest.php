<?php

namespace AliAlizade\Customer\Tests\Feature;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Tests\TestCase;
use AliAlizade\Transfer\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class CustomerBankAccountHistoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_history_of_a_bank_account_can_be_seen()
    {
        $this->withoutExceptionHandling();

        $account = Account::factory()->create();

        $transactions = Transaction::factory()
                                   ->count(8)
                                   ->state(new Sequence(
                                       ['from' => $account->account_number],
                                       ['to' => $account->account_number]
                                   ))->create();

        $response = $this->json(
            'get',
            sprintf('/api/v1/accounts/%s/history', $account->account_number)
        );

        $response->assertSuccessful()
                 ->assertJson(function (AssertableJson $json) use ($transactions) {
                     return $json->hasAll('status', 'data')
                                 ->has('data.history', 8)
                                 ->where('data.history.0.amount', (int) $transactions[0]->amount)
                                 ->where('data.history.7.amount', (int) $transactions[7]->amount)
                                 ->where('data.history.0.from', $transactions[0]->from)
                                 ->where('data.history.7.to', $transactions[7]->to)
                                 ->etc();
                 });
    }
}