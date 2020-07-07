<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('user_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('blog_categories', 'id')->onDelete('set null');

            $table->string('title')->nullable();
            $table->string('slug')->unique()->nullable();
            //pending, accepted, published, canceled
            $table->string('status')->default('pending');
            $table->longText('body')->nullable();
            $table->unsignedInteger('image_id')->nullable();
            $table->foreign('image_id')->references('id')->on('photo_photos')->onDelete('set null');

            $table->integer('total_view')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->text('table_of_content')->nullable();
            $table->dateTime('published_at')->nullable();
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
        Schema::dropIfExists('blog_posts');
    }
}
