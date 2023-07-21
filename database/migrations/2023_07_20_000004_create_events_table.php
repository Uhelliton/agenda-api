<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Agenda\Models\Event;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->date('start_date');
            $table->date('due_date');
            $table->enum('status', [Event::STATUS_OPEN, Event::STATUS_FINALIZATION])->default(Event::STATUS_OPEN);

            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id', 'FkEventType')
                ->references('id')
                ->on('events_types')
                ->onUpdate('cascade');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id', 'FkEventUser')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade');

            $table->date('finalization_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
