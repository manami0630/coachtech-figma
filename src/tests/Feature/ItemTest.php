<?php

namespace Tests\Feature;

use App\Models\Item;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ItemTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_get_items()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertViewHas('items', Item::all());
    }
}