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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->foreignId('region_id')
            ->constrained('regions')
            ->onDelete('cascade')->nullable();
            $table->string('purchase_order_no')->nullable();
            $table->longText('notes')->nullable();
            $table->string('primary_contact_name')->nullable();
            $table->string('primary_contact_postiton')->nullable();
            $table->string('primary_contact_number')->nullable();
            $table->string('primary_contact_email')->nullable();
            $table->string('billing_contact_name')->nullable();
            $table->string('billing_contact_position')->nullable();
            $table->string('billing_contact_number')->nullable();
            $table->string('billing_contact_email')->nullable();
            $table->string('billing_contact_address_1')->nullable();
            $table->string('billing_contact_address_2')->nullable();
            $table->string('billing_post_code')->nullable();
            $table->string('billing_town')->nullable();
            $table->longText('checkin_document_1')->nullable();
            $table->longText('checkin_document_2')->nullable();
            $table->longText('checkin_document_3')->nullable();
            $table->longText('checkin_document_4')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
