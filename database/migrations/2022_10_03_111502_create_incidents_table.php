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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('checkin_residents')->nullable();
            $table->timestamp('incident_datetime')->nullable();
            $table->string('incident_severity')->nullable();
            $table->string('incident_reason')->nullable();
            $table->longText('incident_description')->nullable();
            $table->string('incident_desc_external_report')->nullable();
            $table->json('videos_attachment')->nullable();
            $table->string('incident_external_reporting')->nullable();
            $table->string('status')->nullable();
            $table->string('employee_email')->nullable();
            $table->date('incident_closing_date')->nullable();
            $table->string('closing_employee_email')->nullable();
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
        Schema::dropIfExists('incidents');
    }
};
