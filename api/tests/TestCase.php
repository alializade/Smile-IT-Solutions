<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
