<?php

namespace AliAlizade\Customer\Tests\Units;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use AliAlizade\Customer\Tests\TestCase;
use AliAlizade\Transfer\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_account_can_return_its_customer_info()
    {
        $customer = Customer::factory()->create();
        $account = Account::factory()
                          ->set('customer_id', $customer->id)
                          ->create();

        $this->assertTrue($account->customer->is($customer));
    }

    public function test_an_account_can_fetch_its_history()
    {
        $account = Account::factory()->create();

        $transactions = Transaction::factory()
                                   ->count(4)
                                   ->state(new Sequence(
                                       ['from' => $account->account_number],
                                       ['to' => $account->account_number]
                                   ))->create();

        $this->assertCount(4, $account->history);
    }
}