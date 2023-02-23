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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->json('photo')->nullable();
            $table->longText('card_id')->nullable();
            $table->string('job_title')->nullable();
            $table->string('mobile_number_work')->nullable();
            $table->string('mobile_number_private')->nullable();
            $table->foreignId('employee_group_id')
            ->constrained('employee_groups')
            ->onDelete('cascade')->nullable();
            $table->foreignId('manager_id')
            ->constrained('users')
            ->onDelete('cascade')->nullable();
            $table->longText('about_me')->nullable();
            $table->foreignId('property_id')
            ->constrained('properties')
            ->onDelete('cascade')->nullable();

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
        Schema::dropIfExists('employees');
    }
};
