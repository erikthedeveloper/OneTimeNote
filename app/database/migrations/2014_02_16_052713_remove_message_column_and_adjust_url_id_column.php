<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RemoveMessageColumnAndAdjustUrlIdColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::table('notes', function(Blueprint $table) {
            $table->dropColumn(array('url_id', 'message'));
        });

        Schema::table('notes', function(Blueprint $table) {
            $table->string('url_id', 16);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::table('notes', function(Blueprint $table) {
            $table->dropColumn('url_id');
        });
	}

}
