<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use raw SQL to avoid requiring doctrine/dbal for ->change().
        DB::statement('ALTER TABLE `categories` MODIFY `parent_id` INT NULL');
    }

    public function down(): void
    {
        // Restore original behavior (non-null). Existing data uses 0 for roots.
        DB::statement('ALTER TABLE `categories` MODIFY `parent_id` INT NOT NULL');
    }
};

