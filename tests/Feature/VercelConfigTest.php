<?php

namespace Tests\Feature;

use Tests\TestCase;

class VercelConfigTest extends TestCase
{
    public function test_vercel_routes_include_avif_assets(): void
    {
        $config = json_decode(file_get_contents(base_path('vercel.json')), true, 512, JSON_THROW_ON_ERROR);

        $this->assertNotEmpty($config['routes']);

        $routeSources = array_column($config['routes'], 'src');
        $this->assertTrue(
            collect($routeSources)->contains(fn ($source) => str_contains($source, 'avif')),
            'Expected at least one Vercel route to include avif assets.'
        );
    }
}
