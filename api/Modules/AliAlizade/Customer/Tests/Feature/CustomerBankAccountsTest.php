<?php

namespace AliAlizade\Customer\Tests\Feature;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
}