<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('products')) {
            return;
        }

        $requiredColumns = ['id', 'name', 'description', 'price', 'created_at', 'updated_at'];
        $existingColumns = Schema::getColumnListing('products');

        if (count(array_diff($requiredColumns, $existingColumns)) === 0) {
            return;
        }

        if (DB::table('products')->exists()) {
            throw new RuntimeException(
                'The products table has an incomplete schema and contains data. Resolve it without dropping existing products.'
            );
        }

        Schema::drop('products');

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2);
            $table->timestamps();
        });
    }
};
