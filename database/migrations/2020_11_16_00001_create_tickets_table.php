<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-tickets.database.tickets-table'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('opener_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('subject');
            $table->enum('priority', config('laravel-tickets.priorities'));
            $table->enum('state', [ 'OPEN', 'ANSWERED', 'CLOSED' ])->default('OPEN');
            $table->timestamps();

            if (! config('laravel-tickets.models.uuid')) {
                $table->foreign('opener_id')
                    ->on(config('laravel-tickets.database.users-table'))->references('id');
                $table->foreign('user_id')
                    ->on(config('laravel-tickets.database.users-table'))->references('id');
                $table->foreign('category_id')
                    ->on(config('laravel-tickets.database.ticket-categories-table'))->references('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('laravel-tickets.database.tickets-table'));
    }
}
