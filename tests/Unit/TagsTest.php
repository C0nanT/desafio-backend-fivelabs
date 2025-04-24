<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Tags;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

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

    /** @test */
    public function it_can_update_a_tag()
    {
        $user = User::factory()->create();

        $tag = Tags::factory()->create([
            'created_by' => $user->id,
        ]);

        $oldData = [
            'name' => $tag->name,
            'slug' => $tag->slug,
            'color' => $tag->color
        ];

        $updatedData = [
            'name' => 'Tag Atualizada',
            'slug' => 'tag-atualizada',
            'color' => '#33FF57',
        ];

        $tag->update($updatedData);

        $this->assertEquals('Tag Atualizada', $tag->name);
        $this->assertEquals('tag-atualizada', $tag->slug);
        $this->assertEquals('#33FF57', $tag->color);
    }

    /** @test */
    public function it_can_delete_a_tag()
    {
        $user = User::factory()->create();

        $tag = Tags::factory()->create([
            'created_by' => $user->id,
        ]);

        $tagId = $tag->id;

        $tag->delete();
       
        $this->assertDatabaseMissing('tags', [
            'id' => $tagId,
            'created_by' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_retrieve_a_tag()
    {
        $user = User::factory()->create();

        $tag = Tags::factory()->create([
            'created_by' => $user->id,
        ]);

        $retrievedTag = Tags::find($tag->id);

        $this->assertInstanceOf(Tags::class, $retrievedTag);
        $this->assertEquals($tag->name, $retrievedTag->name);
        $this->assertEquals($tag->slug, $retrievedTag->slug);
        $this->assertEquals($tag->color, $retrievedTag->color);
        $this->assertEquals($user->id, $retrievedTag->created_by);
    }


    /** @test */
    public function it_can_list_all_tags()
    {
        $user = User::factory()->create();

        $tags = Tags::factory()->count(3)->create([
            'created_by' => $user->id,
        ]);

        foreach($tags as $tag) {
            $this->assertInstanceOf(Tags::class, $tag);
            $this->assertEquals($user->id, $tag->created_by);
        }

        $this->assertCount(3, Tags::all());
    }

}
