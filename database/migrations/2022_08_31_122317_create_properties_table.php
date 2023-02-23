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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('property_name');
            $table->string('number_of_units')->nullable();
            $table->longText('description')->nullable();
            $table->longText('address1')->nullable();
            $table->longText('address2')->nullable();
            $table->longText('town')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('no_of_units')->nullable();
            $table->string('postcode')->nullable();
            $table->longText('gps_coordinates')->nullable();
            
            $table->foreignId('primary_manager_id')
            ->constrained('users')->nullable();
            $table->foreignId('regional_manager_id')
            ->constrained('users')->nullable();
            $table->string('electric_meter_id')->nullable();
            $table->string('gas_meter_id')->nullable();
            $table->string('water_meter_id')->nullable();
            $table->string('solar_panels')->nullable();
            $table->json('primary_photo')->nullable();
            $table->json('videos_attachment')->nullable();
            $table->json('images_attachment')->nullable();
            $table->json('property_video_internal')->nullable();
            $table->json('property_video_external')->nullable();

            $table->string('hmo_license')->nullable();
            $table->date('hmo_license_expiry_date')->nullable();

            $table->string('insurance_policy')->nullable();
            $table->date('insurance_policy_exiry_date')->nullable();

            $table->string('eicr')->nullable();
            $table->date('eicr_exiry_date')->nullable();

            $table->string('gas_certificate')->nullable();
            $table->date('gas_certificate_exiry_date')->nullable();

            $table->string('pat_test')->nullable();
            $table->date('pat_test_exiry_date')->nullable();

            $table->string('epc_certificate')->nullable();
            $table->date('epc_certificate_exiry_date')->nullable();

            $table->longText('house_rules')->nullable();

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
        Schema::dropIfExists('properties');
    }
};
