<?php

use App\Domain\Contracts\CategoryContract;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create(CategoryContract::TABLE, function (Blueprint $table) {
            $table->id();
            $table->string(CategoryContract::TITLE);
            $table->string(CategoryContract::TITLE_KZ)->nullable();
            $table->string(CategoryContract::TITLE_EN)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(CategoryContract::TABLE);
    }
}
