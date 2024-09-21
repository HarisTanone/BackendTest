<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $author;

    protected function setUp(): void
    {
        parent::setUp();
        $this->author = Author::factory()->create([
            'id' => (string) Str::uuid()
        ]);
    }

    /** @test */
    public function dapat_menambahkan_buku()
    {
        $data = [
            'title' => 'Buku Baru',
            'description' => 'Deskripsi buku baru.',
            'publish_date' => '2024-01-01',
            'author_id' => $this->author->id,
        ];

        $response = $this->postJson('/api/book', $data);

        $response->assertStatus(201)
                 ->assertJson(['title' => 'Buku Baru']);

        $this->assertDatabaseHas('books', ['title' => 'Buku Baru']);
    }

    /** @test */
    public function dapat_melihat_semua_buku()
    {
        Book::factory()->count(3)->create(['author_id' => $this->author->id]);

        $response = $this->getJson('/api/book');

        $response->assertStatus(200)
                 ->assertJsonCount(3);
    }

    /** @test */
    public function dapat_melihat_satu_buku()
    {
        $book = Book::factory()->create(['author_id' => $this->author->id]);
        $response = $this->getJson("/api/book/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson(['title' => $book->title]);
    }

    /** @test */
    public function dapat_memperbarui_buku()
    {
        $book = Book::factory()->create(['author_id' => $this->author->id]);

        $data = [
            'title' => 'Buku Diupdate',
            'description' => 'Deskripsi yang sudah diupdate.',
            'publish_date' => '2024-02-02',
            'author_id' => $this->author->id,
        ];

        $response = $this->putJson("/api/book/{$book->id}", $data);

        $response->assertStatus(200)
                 ->assertJson(['title' => 'Buku Diupdate']);
        $this->assertDatabaseHas('books', ['title' => 'Buku Diupdate']);
    }

    /** @test */
    public function dapat_menghapus_buku()
    {
        $book = Book::factory()->create(['author_id' => $this->author->id]);

        $response = $this->deleteJson("/api/book/{$book->id}");

        $response->assertStatus(200)
                 ->assertJson(['message' => 'Book deleted']);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
