<?php

namespace Tests;

use App\DataProviders\Models\Listener;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginAsListener(Listener $listener = null)
    {
        $listener = $listener ?? Listener::factory()->create();

        $this->actingAs($listener);

        return $listener;
    }
}
