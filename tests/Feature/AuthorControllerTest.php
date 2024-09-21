<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

class AuthorControllerTest extends TestCase
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
    public function dapat_menambahkan_author_baru()
    {
        $data = [
            'name' => 'Author Baru',
            'bio' => 'Ini adalah biografi author baru.',
            'birth_date' => '1990-01-01',
        ];

        $response = $this->postJson('/api/author', $data);

        $response->assertStatus(201)
                 ->assertJson(['name' => 'Author Baru']);

        $this->assertDatabaseHas('authors', ['name' => 'Author Baru']);
    }

    /** @test */
    public function dapat_mengambil_semua_author()
    {
        $response = $this->getJson('/api/author');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     '*' => ['id', 'name', 'bio', 'birth_date']
                 ]);
    }

    /** @test */
    public function dapat_melihat_satu_author()
    {
        $response = $this->getJson("/api/author/{$this->author->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'id' => $this->author->id,
                    'name' => $this->author->name,
                    'bio' => $this->author->bio,
                    'birth_date' => $this->author->birth_date,
                ]);
    }

    /** @test */
    public function dapat_memperbarui_author()
    {
        $data = [
            'name' => 'Author Diupdate',
            'bio' => 'Biografi yang sudah diupdate.',
            'birth_date' => '1991-02-02',
        ];

        $response = $this->putJson("/api/author/{$this->author->id}", $data);

        $response->assertStatus(200)
                ->assertJson(['message' => 'Author updated']);

        $this->assertDatabaseHas('authors', ['name' => 'Author Diupdate']);
    }

    /** @test */
    public function dapat_menghapus_author()
    {
        $response = $this->deleteJson("/api/author/{$this->author->id}");

        $response->assertStatus(200)
                ->assertJson(['message' => 'Author deleted']);

        $this->assertDatabaseMissing('authors', ['id' => $this->author->id]);
    }

}
