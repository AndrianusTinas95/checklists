<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChecklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain')->nullable();
            $table->string('description');
            $table->boolean('is_complated')->default(0);
            $table->timestamp('completed_at')->default(DB::raw('NULL'))->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamp('due')->default(DB::raw('NULL'))->nullable();
            $table->smallInteger('urgency')->nullable();
            $table->unsignedInteger('object_id')->nullable();
            $table->timestamps();

            $table->foreign('object_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists');
    }
}
