<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // DD, CL, CA, BB, CV AR, SS, Misc
            $table->string('rarity'); // Common, Rare, Elite, Super rare, Ultra 
            $table->string('faction'); // Iris Libre, Vichya Doominion 
            $table->string('Performace'); // {FP,HP,AA,SP,AVI,TRP}
            $table->string('note'); // personal note
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
