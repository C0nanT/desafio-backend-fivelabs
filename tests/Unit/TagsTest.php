<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Tags;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_tag()
    {
        $user = User::factory()->create();

        $tagData = [
            'name' => 'Teste de Tag',
            'slug' => 'teste-de-tag',
            'created_by' => $user->id,
            'color' => '#FF5733',
        ];

        $tag = Tags::factory()->create($tagData);

        $this->assertInstanceOf(Tags::class, $tag);
        $this->assertEquals('Teste de Tag', $tag->name);
        $this->assertEquals('teste-de-tag', $tag->slug);
        $this->assertEquals('#FF5733', $tag->color);
        $this->assertEquals($user->id, $tag->created_by);
    }
}
