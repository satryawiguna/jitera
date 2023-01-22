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
        Schema::create('follows', function (Blueprint $table) {
            $table->uuid('follower_id', 36);
            $table->foreign('follower_id')
                ->references('id')
                ->on('users');

            $table->uuid('followed_id', 36)->nullable();
            $table->foreign('followed_id')
                ->references('id')
                ->on('users');

            $table->timestamps();

            $table->unique(['follower_id', 'followed_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('follows');
    }
};
