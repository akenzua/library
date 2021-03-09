<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Book;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function a_book_can_be_added_to_the_library (){
        
        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Victor'
        ]);

        $book = Book::first();

        $this->assertCount(1, Book::all());

        $response->assertRedirect('/books/' . $book->id);
    }


    /** @test */
    public function a_title_is_required(){

        $response = $this->post('/books', [
            'title' => '',
            'author' => 'Victor'
        ]); 

        $response->assertSessionHasErrorsIn('title');

    }


    /** @test */
    public function a_author_is_required(){

        $response = $this->post('/books', [
            'title' => 'Cool title',
            'author' => ''
        ]); 

        $response->assertSessionHasErrorsIn('author');

    }


    /** @test */
    public function a_book_can_be_updated(){


        $this->post('/books', [
            'title' => 'Cool title',
            'author' => 'Author'
        ]); 

        $book = Book::first();

        $response = $this->patch('/books/' .$book->id,[
            'title' => 'New title',
            'author' => 'New author',
        ]);

        $this->assertEquals('New title', Book::first()->title);
        $this->assertEquals('New author', Book::first()->author);
        $response->assertRedirect('/books/' . $book->id);


    }

    /** @test */
    public function a_book_can_be_deleted(){

        $this->post('/books', [
            'title' => 'Cool title',
            'author' => 'Author'
        ]); 

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete('/books/' .$book->id);

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
