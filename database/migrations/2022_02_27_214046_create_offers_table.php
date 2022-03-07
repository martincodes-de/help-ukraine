<?php

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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->longText("description");
            $table->mediumText("contact");
            $table->double("lat");
            $table->double("lng");
            $table->date("visible_until");

            $table->unsignedBigInteger("offer_category_id");
            $table->foreign("offer_category_id")->references("id")->on("offer_categories");

            $table->dateTime("reviewed")->nullable();
            $table->text("moderation_notice")->nullable();
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
        Schema::dropIfExists('offers');
    }
};
