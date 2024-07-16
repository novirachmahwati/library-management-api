<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Author;

class AuthorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_author()
    {
        $authorData = Author::factory()->make()->toArray();

        $response = $this->postJson('/api/1.0/authors', $authorData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('authors', $authorData);
    }

    /** @test */
    public function it_can_retrieve_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->getJson('/api/1.0/authors/' . $author->id);

        $response->assertStatus(200)
                 ->assertJson($author->toArray());
    }

    /** @test */
    public function it_can_update_an_author()
    {
        $author = Author::factory()->create();
        $updatedData = Author::factory()->make()->toArray();

        $response = $this->putJson('/api/1.0/authors/' . $author->id, $updatedData);

        $response->assertStatus(204);
        $this->assertDatabaseHas('authors', $updatedData);
    }

    /** @test */
    public function it_can_delete_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson('/api/1.0/authors/' . $author->id);

        $response->assertStatus(204);
    }

    /** @test */
    public function it_handles_author_not_found()
    {
        $response = $this->getJson('/api/1.0/authors/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function name_is_required_to_create_an_author()
    {
        $response = $this->postJson('/api/1.0/authors', [
            'bio' => 'Some bio',
            'birth_date' => '2000-01-01'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    /** @test */
    public function name_is_required_to_update_an_author()
    {
        $author = Author::factory()->create();

        $response = $this->putJson('/api/1.0/authors/' . $author->id, [
            'name' => '',
            'bio' => 'Updated bio',
            'birth_date' => '2000-01-01'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

}
