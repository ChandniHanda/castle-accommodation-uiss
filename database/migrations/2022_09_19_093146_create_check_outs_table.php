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
        Schema::create('check_outs', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('checkin_id')
            // ->constrained('check_ins')
            // ->onDelete('cascade')->nullable();    
            $table->string('checkout_date')-> nullable();
            $table->string('checkout_time')-> nullable();
            $table->foreignId('employee_id')
            ->constrained('employees')
            ->onDelete('cascade')->nullable();
            $table->longText('link_checkin_video')->nullable();
            $table->enum('reason_of_leaving', ['Evicted', 'Left Voluntarily','Other CA Accomodation','Otherhousing/Rehoused','Arrested','Hospital'])->nullable();
            $table->string('repair_maintenance_needed');
            $table->string('procurement_needed');
            $table->json('videos_attachment')->nullable();
            $table->longText('comments')->nullable();



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
        Schema::dropIfExists('check_outs');
    }
};
