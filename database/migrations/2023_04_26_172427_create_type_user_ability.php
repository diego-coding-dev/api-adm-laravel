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
        Schema::create('type_user_abilities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_user_id');
            $table->unsignedBigInteger('ability_id');
            $table->timestamps();
            $table->foreign('type_user_id')->references('id')->on('type_users')->cascadeOnUpdate();
            $table->foreign('ability_id')->references('id')->on('abilities')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('type_user_abilities');
    }
};
