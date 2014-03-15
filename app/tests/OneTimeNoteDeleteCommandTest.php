<?php
use OneTimeNote\Commands\OneTimeNoteDeleteCommand;
use Symfony\Component\Console\Tester\CommandTester;

class OneTimeNoteDeleteCommandTest extends TestCase {
    protected $mock;

    public function setUp() {
        parent::setUp();

        $this->mock = Mockery::mock('OneTimeNote\Interfaces\NoteRepositoryInterface');
    }

    public function tearDown() {
        Mockery::close();
    }

    public function test_with_no_days_specified_success()
    {
        $this->mock->shouldReceive('deleteNotesOlderThan')
                   ->once()
                   ->andReturn(true);

        $command = new OneTimeNoteDeleteCommand($this->mock);

        $tester = new CommandTester($command);

        // Don't pass any days
        $tester->execute([]);
        $this->assertInternalType('string', $tester->getDisplay());
        $this->assertContains("Success", $tester->getDisplay());
    }

    public function test_with_days_as_int_success()
    {
        $this->mock->shouldReceive('deleteNotesOlderThan')
                   ->once()
                   ->andReturn(true);

        $command = new OneTimeNoteDeleteCommand($this->mock);

        $tester = new CommandTester($command);

        // Pass Days
        $tester->execute(['--days' => 20]);
        $this->assertInternalType('string', $tester->getDisplay());
        $this->assertContains("Success", $tester->getDisplay());
    }

    public function test_no_notes_found_with_allotted_time()
    {
        $this->mock->shouldReceive('deleteNotesOlderThan')
                   ->once()
                   ->andReturn(false);

        $command = new OneTimeNoteDeleteCommand($this->mock);

        $tester = new CommandTester($command);

        // Pass Days
        $tester->execute(['--days' => 20]);
        $this->assertInternalType('string', $tester->getDisplay());
        $this->assertContains("No notes", $tester->getDisplay());
    }

    public function test_delete_command_with_days_as_wrong_data_type_failure()
    {
        $command = new OneTimeNoteDeleteCommand($this->mock);

        $tester = new CommandTester($command);

        // Pass Days as Float
        $tester->execute(['--days' => 0.2]);
        $this->assertInternalType('string', $tester->getDisplay());
        $this->assertContains("Failure", $tester->getDisplay());

        // Pass Days as String
        $tester->execute(['--days' => 'I Love You']);
        $this->assertInternalType('string', $tester->getDisplay());
        $this->assertContains("Failure", $tester->getDisplay());
    }
}