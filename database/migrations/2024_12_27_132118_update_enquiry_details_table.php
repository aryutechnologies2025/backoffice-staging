<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            // Drop an existing column (replace 'old_column_name' with the actual column name)
            $table->dropColumn('male_female_count');

            // Add a new column (replace 'new_column_name' and 'data_type' as needed)
            $table->string('male_count')->nullable(); // Example: nullable string column
            $table->string('female_count')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('enquiry_details', function (Blueprint $table) {
            // Add the dropped column back
            $table->string('male_female_count')->nullable();

            // Drop the newly added column
            $table->dropColumn('male_count');
            $table->dropColumn('female_count');

        });
    }
};
