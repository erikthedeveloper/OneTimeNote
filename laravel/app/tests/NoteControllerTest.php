<?php

    use OneTimeNote\Models\Note;

    class NoteControllerTest extends TestCase {

        protected $mock;
        protected $note;

        public function setUp() {
            parent::setUp();

            $this->mock = Mockery::mock('OneTimeNote\Interfaces\NoteRepositoryInterface');

            $this->note = new Note;
            $this->note->message = 'This is a message';
            $this->note->secure_note = 'This is a secure note';
            $this->note->email = 'email@address.com';
            $this->note->key = '1234567890abcdef';
            $this->note->url_id = '1234567890abcdef';
        }

        public function tearDown() {
            Mockery::close();
            unset($this->note);
        }

        public function test_get_note_success()
        {
            // Arrange
            $this->mock->shouldReceive('find')->once()->andReturn($this->note);
            $this->mock->shouldReceive('delete')->once()->andReturnNull();

            $Mailer = Mockery::mock('OneTimeNote\Interfaces\NoteMailerInterface');
            $Mailer->shouldReceive('to')->once()->andReturnNull();

            $this->app->instance('OneTimeNote\Interfaces\NoteRepositoryInterface', $this->mock);
            $this->app->instance('OneTimeNote\Interfaces\NoteMailerInterface', $Mailer);

            // Act
            $response = $this->action('GET', 'OneTimeNote\Controllers\NoteController@getNote', ['url_id' => '1234567890abcdefg', 'key' => '1234567890abcdefg']);

            // Assert
            $this->assertResponseOk();
            $this->assertInternalType('string', $response->getContent());
        }

        public function test_get_note_fails()
        {
            // Arrange
            $this->mock->shouldReceive('find')->once()->andReturnNull();
            $this->app->instance('OneTimeNote\Interfaces\NoteRepositoryInterface', $this->mock);

            // Act
            $this->action('GET', 'OneTimeNote\Controllers\NoteController@getNote', ['url_id' => '1234567890abcdefg', 'key' => '1234567890abcdefg']);

            // Assert
            $this->assertResponseStatus(404);
        }

        public function test_post_note_success()
        {
            // Arrange
            $this->mock->shouldReceive('existingNote')->once()->andReturn(false);
            $this->mock->shouldReceive('create')->once()->andReturn($this->note);
            $this->app->instance('OneTimeNote\Interfaces\NoteRepositoryInterface', $this->mock);

            // Act
            $response = $this->action('POST', 'OneTimeNote\Controllers\NoteController@postNote');

            // Assert
            $this->assertResponseStatus(201);
            $this->assertInternalType('string', $response->getContent());
        }

        public function test_post_note_which_already_exists_within_time_limit()
        {
            // Arrange
            $this->mock->shouldReceive('existingNote')->once()->andReturn($this->note);
            $this->app->instance('OneTimeNote\Interfaces\NoteRepositoryInterface', $this->mock);

            // Act
            $note = $this->action('POST', 'OneTimeNote\Controllers\NoteController@postNote');

            // Assert
            $this->assertResponseStatus(403);
            $this->assertInternalType('string', $note->getContent());
        }

        public function test_post_note_fails_validation()
        {
            // Arrange
            $this->mock->shouldReceive('existingNote')->once()->andReturn(false);
            $this->mock->shouldReceive('create')->once()->andReturnNull(); // Failed validation returns null anyway
            $this->app->instance('OneTimeNote\Interfaces\NoteRepositoryInterface', $this->mock);

            // Act
            $note = $this->action('POST', 'OneTimeNote\Controllers\NoteController@postNote');

            // Assert
            $this->assertResponseStatus(400);
            $this->assertInternalType('string', $note->getContent());
        }
    }