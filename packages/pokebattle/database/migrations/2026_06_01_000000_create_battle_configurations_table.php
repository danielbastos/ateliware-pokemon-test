<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('battle_configurations', function (Blueprint $table): void {
            $table->id();
            $table->unsignedTinyInteger('concorrents')->default(2);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('battle_configurations');
    }
};
