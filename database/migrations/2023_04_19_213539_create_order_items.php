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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('storage_id');
            $table->unsignedBigInteger('order_id');
            $table->integer('quantity', false, true);
            $table->boolean('is_finished')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('storage_id')->references('id')->on('storages')->cascadeOnUpdate();
            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
