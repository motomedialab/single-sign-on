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
        Schema::create('sso_tokens', function (Blueprint $table) {

            $table->primary('id');
            $table->foreignIdFor(\App\Models\User::class);

            $table->longText('access_token');
            $table->longText('refresh_token');

            $table->timestamps();
            $table->dateTime('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sso_tokens');
    }
};
