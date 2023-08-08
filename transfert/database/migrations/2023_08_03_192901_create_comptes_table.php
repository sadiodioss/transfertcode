<?php

use App\Models\client;
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
        Schema::create('comptes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(client::class)->constrained();
            $table->enum('etat',[1,2]);
            $table->enum('out',[1,2])->default('out',1);
            $table->enum('opperateur',['OM','WV','WR','CB']);
            $table->float('solde');
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
        Schema::dropIfExists('comptes');
    }
};
