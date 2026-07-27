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
        $table->id(); // Tu campo 'id' (BigInteger, Auto-increment)
        $table->string('name'); // Tu campo 'nombre'
        $table->string('email')->unique(); // Tu campo 'correo'
        $table->string('phone')->nullable(); // Tu campo 'telefono' (Es buena idea dejarlo nullable por si no es obligatorio al inicio)
        $table->string('password'); // Tu campo 'password'
        
        // Tu campo 'estado' (Activo, Inactivo, Suspendido, etc.)
        // Usar un booleano o un string corto con un valor por defecto es ideal
        $table->string('status')->default('active'); 
        
        $table->timestamp('last_login_at')->nullable(); // Tu campo 'ultimo acceso'
        
        // Tu campo 'verificacion' (Se alinea con 'email_verified_at' de Laravel)
        $table->timestamp('email_verified_at')->nullable(); 
        
        $table->rememberToken(); // Tu campo 'remember_token' (Mecanismo nativo de Laravel)
        $table->timestamps(); // Crea automáticamente 'created_at' y 'updated_at'
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
