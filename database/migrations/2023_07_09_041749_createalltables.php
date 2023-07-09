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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf');
            $table->string('password');
        });

        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('id_owner')->constrained('users', 'id');
        });

        Schema::create('unitPeoples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->string('name');
            $table->date('birthdate');
        });

        Schema::create('unitVehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->string('title');
            $table->string('color');
            $table->string('plate');
        });

        Schema::create('unitPets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->string('name');
            $table->string('race');
        });

        Schema::create('walls', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('body');
            $table->datetime('dateCreated');
        });

        Schema::create('wallLikes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_wall')->constrained('walls', 'id');
            $table->foreignId('id_user')->constrained('users', 'id');
        });

        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('fileUrl');
        });

        Schema::create('billets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->string('title');
            $table->string('fileUrl');
        });

        Schema::create('warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->string('title');
            $table->string('status')->default('IN_REVIEW'); //IN_REVIEW - RESOLVED
            $table->date('dateCreated');
            $table->date('photos');
        });

        Schema::create('foundAndLost', function (Blueprint $table) {
            $table->id();
            $table->string('status')->default('LOST'); //LOST - RECOVERED
            $table->string('photo');
            $table->string('description');
            $table->string('where');
            $table->date('dateCreated');
        });

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->integer('allowed')->default(1);
            $table->string('title');
            $table->string('cover');
            $table->string('days'); //0,1,2,3,4,5,6
            $table->time('start_time');
            $table->time('end_time');
        });

        Schema::create('areaDisabledDays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_area')->constrained('areas', 'id');
            $table->date('day');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unit')->constrained('units', 'id');
            $table->foreignId('id_area')->constrained('areas', 'id');
            $table->datetime('reservation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('units');
        Schema::dropIfExists('unitPeoples');
        Schema::dropIfExists('unitVehicles');
        Schema::dropIfExists('unitPets');
        Schema::dropIfExists('walls');
        Schema::dropIfExists('wallLikes');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('billets');
        Schema::dropIfExists('warnings');
        Schema::dropIfExists('foundAndLost');
        Schema::dropIfExists('areas');
        Schema::dropIfExists('areaDisabledDays');
        Schema::dropIfExists('reservations');
    }
};
