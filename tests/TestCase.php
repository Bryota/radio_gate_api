<?php

namespace Tests;

use App\DataProviders\Models\Listener;
use App\DataProviders\Models\Admin;
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

    public function loginAsAdmin(Admin $admin = null)
    {
        $admin = $admin ?? Admin::factory()->create();

        $this->actingAs($admin, 'admin');

        return $admin;
    }
}
