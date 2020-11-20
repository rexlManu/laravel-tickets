<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-tickets.database.ticket-activities-table'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->morphs('targetable');
            $table->enum('type', [ 'CREATE', 'CLOSE', 'OPEN', 'ANSWER' ]);
            $table->timestamps();

            if (! config('laravel-tickets.models.uuid')) {
                $table->foreign('ticket_id')
                    ->on(config('laravel-tickets.database.tickets-table'))->references('id');
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
        Schema::dropIfExists(config('laravel-tickets.database.ticket-activities-table'));
    }
}
