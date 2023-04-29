<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Schema\ForeignKeyDefinition;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
<<<<<<< Updated upstream
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
=======
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->foreignIdFor(Product::class);
            $table->char('product_name');
            $table->string('product_image')->default('https://student.valuxapps.com/storage/assets/defaults/user.jpg');
            $table->float('amount');
>>>>>>> Stashed changes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
