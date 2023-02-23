<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->string('resident_fullname');
            $table->string('resident_nickname')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('mobile_number')->nullable();
            $table->foreignId('customer_id')
            ->constrained('customers')
            ->onDelete('cascade')->nullable();
            $table->longText('command')->nullable();
            $table->foreignId('room_no')
            ->constrained('property_units')
            ->onDelete('cascade')->nullable();
            $table->string('date')->nullable();
            $table->string('time_checkin')->nullable();
            $table->string('door_code')->nullable();
            $table->string('access_fob')->nullable();
            $table->longText('comment')->nullable();
            $table->json('videos_attachment')->nullable();
            $table->json('resident_signature')->nullable();
            $table->string('evicted_previous_accomodation')->nullable();
            $table->string('terms_conditions')->nullable();





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
        Schema::dropIfExists('check_ins');
    }
};
