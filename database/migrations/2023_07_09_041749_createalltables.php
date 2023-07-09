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
            $table->string('status')->default('IN_REVIEW');
            $table->date('dateCreated');
            $table->date('photos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
