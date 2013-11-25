<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortletsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('portlets', function(Blueprint $table)
		{
			$table->increments('id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('type')->default('model');   //model | view
            $table->text('body')->default('');
            $table->string('uri')->nullable();
			$table->timestamps();

            $table->engine = 'InnoDB';
            $table->unique('name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('portlets');
	}

}
