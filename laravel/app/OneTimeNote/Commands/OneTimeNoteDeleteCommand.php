<?php

namespace OneTimeNote\Commands;

use OneTimeNote\Interfaces\NoteRepositoryInterface as Note;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


class OneTimeNoteDeleteCommand extends Command {

	protected $note;

    /**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'oneTimeNote:delete';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Delete all notes older than specified number of days. 30 by default.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct(Note $note)
	{
        parent::__construct();
        $this->note = $note;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        $days = $this->option('days');

        if (!$days) {
            $days = 30;
        }

        // @TODO - Artisan passes the number of days as a string, need to figure out if I can parse it as an int and than check for that
        if(!is_integer($days)) {
            $this->error("Failure - The 'Days' option must be a valid integer");

            return false;
        }

        $this->note->deleteNotesOlderThan($days);

        $this->info('Success - Notes older than ' . $days . ' days(s) have been deleted.');
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('days', null, InputOption::VALUE_OPTIONAL, 'Removes a note that is N old.', null),
		);
	}

}
