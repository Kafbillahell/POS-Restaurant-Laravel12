<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilePhotosTable extends Migration
{
    public function up()
    {
        Schema::create('profile_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke users
            $table->string('photo_path'); // path atau nama file foto
            $table->timestamps();
            $table->string('filter')->default('none');

        });
    }

    public function down()
    {
        Schema::dropIfExists('profile_photos');
    }
}
