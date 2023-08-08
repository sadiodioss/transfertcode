<?php

use App\Models\client;
use App\Models\compte;

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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Compte::class)->nullable();
            $table->foreignIdFor(client::class)->nullable();
            $table->float('montant');
            $table->string('code')->nullable();
            $table->enum('etat',[1,2]);
            $table->enum('opperateur',['OM','WV','WR','CB']);
            $table->enum('type',['depot','retrait','transfert']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
