<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    function can_get_all_books(){
        $books = Book::factory(4)->create();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title
            ])->assertJsonFragment([
                'title' => $books[3]->title
            ]);
    }

    /** @test */
    function can_get_one_book(){
        $book = Book::factory()->create();

        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title
            ]);
    }

    /** @test */
    function can_create_a_book(){

        $this->postJson(route('books.store'), [])->assertJsonValidationErrors('title');

        $this->postJson(route('books.store'), [
            'title' => 'New Book'
        ])->assertJsonFragment([
            'title' => 'New Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'New Book'
        ]);
    }

    /** @test */
    function can_update_a_book(){
        $book = Book::factory()->create();

        $this->patchJson(route('books.update', $book), [])->assertJsonValidationErrors('title');

        $this->patchJson(route('books.update', $book), [
            'title' => 'Updated Book'
        ])->assertJsonFragment([
            'title' => 'Updated Book'
        ]);

        $this->assertDatabaseHas('books', [
            'title' => 'Updated Book'
        ]);
    }

    /** @test */
    function can_delete_a_book(){
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
