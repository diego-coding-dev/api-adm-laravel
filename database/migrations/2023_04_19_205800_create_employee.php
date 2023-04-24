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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_user_id');
            $table->string('name', 220);
            $table->string('email', 150);
            $table->string('password', 255);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('type_user_id')->references('id')->on('type_users')->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
