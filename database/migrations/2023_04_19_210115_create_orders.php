<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('employee_id');
            $table->string('register', 15);
            $table->decimal('total', 8, 2, true)->nullable();
            $table->boolean('is_settled')->default(false);
            $table->boolean('id_canceled')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('client_id')->references('id')->on('clients')->cascadeOnUpdate();
            $table->foreign('employee_id')->references('id')->on('employees')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
