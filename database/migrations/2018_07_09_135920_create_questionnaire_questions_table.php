<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionnaireQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionnaire_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('questionnaire_id');
            $table->string('question', 500);
            $table->string('type', 20);
            $table->foreign('questionnaire_id')->references('id')->on('questionnaires');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questionnaire_questions');
    }
}
