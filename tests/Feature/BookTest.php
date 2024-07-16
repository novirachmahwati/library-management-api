<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Author;
use App\Models\Book;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_book()
    {
        $author = Author::factory()->create();
        $bookData = Book::factory()->make(['author_id' => $author->id])->toArray();

        $response = $this->postJson('/api/1.0/books', $bookData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('books', $bookData);
    }

    /** @test */
    public function it_can_retrieve_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->getJson('/api/1.0/books/' . $book->id);

        $response->assertStatus(200)
                 ->assertJson($book->toArray());
    }

    /** @test */
    public function it_can_update_a_book()
    {
        $book = Book::factory()->create();
        $updatedData = Book::factory()->make(['author_id' => $book->author_id])->toArray();

        $response = $this->putJson('/api/1.0/books/' . $book->id, $updatedData);

        $response->assertStatus(204);
        $this->assertDatabaseHas('books', $updatedData);
    }

    /** @test */
    public function it_can_delete_a_book()
    {
        $book = Book::factory()->create();

        $response = $this->deleteJson('/api/1.0/books/' . $book->id);

        $response->assertStatus(204);
    }

    /** @test */
    public function it_handles_book_not_found()
    {
        $response = $this->getJson('/api/1.0/books/999');

        $response->assertStatus(404);
    }

    /** @test */
    public function title_is_required_to_create_a_book()
    {
        $author = Author::factory()->create();

        $response = $this->postJson('/api/1.0/books', [
            'description' => 'Some description',
            'publish_date' => '2023-01-01',
            'author_id' => $author->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }

    /** @test */
    public function title_is_required_to_update_a_book()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->putJson('/api/1.0/books/' . $book->id, [
            'title' => '',
            'description' => 'Updated description',
            'publish_date' => '2023-01-01',
            'author_id' => $author->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('title');
    }
}
