<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavigationTest extends TestCase
{
    public function test_mobile_navigation_toggle_is_rendered(): void
    {
        $html = view('layouts.navigation')->render();

        $this->assertStringContainsString('Open navigation menu', $html);
        $this->assertStringContainsString('sm:hidden', $html);
    }
}
