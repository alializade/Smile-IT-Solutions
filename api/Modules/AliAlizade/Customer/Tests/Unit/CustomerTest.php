<?php

namespace AliAlizade\Customer\Tests\Units;

use AliAlizade\Customer\Models\Account;
use AliAlizade\Customer\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_customer_can_get_their_owned_accounts()
    {
        /** @var Customer $customer */
        $customer = Customer::factory()->create();

        $account = Account::factory()
                          ->set('customer_id', $customer->id)
                          ->set('current_amount', 310)
                          ->create();

        $this->assertEquals(
            $account->current_amount,
            $customer->accounts[0]->current_amount
        );
    }
}