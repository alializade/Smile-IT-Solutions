<?php

namespace AliAlizade\Customer\Tests;

use Str;

class TestCase extends \Tests\TestCase
{
    protected function assertValidationOf(
        string $key,
        string $url,
        array $data,
        ?string $error = null,
        ?string $method = 'post'
    ): void {
        // $this->withoutExceptionHandling();

        $response = $this->json($method, $url, $data);

        $response->assertStatus(422);

        $fieldLabel = Str::of($key)->replace('_', ' ');

        if ($error) {
            $this->assertMatchesRegularExpression(
                "/.*$fieldLabel.* $error.*/",
                $response->json()['errors']['message']
            );
        }
    }

}