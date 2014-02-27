<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('notes', function($table)
        {
            $table->increments('id');
            $table->string('url_id', 32);
            $table->longText('secure_note');
            $table->string('message')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address', 40);
            $table->timestamps();
        });
    }

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('notes');
    }

}
