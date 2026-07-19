<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Note: confirmation_file_path/type stay NOT NULL at the DB level
            // (changing that needs doctrine/dbal). The web booking flow no
            // longer uploads a file, so the controller now stores empty
            // strings for those two columns instead.

            // Payment details captured at the "Payment" wizard step.
            $table->string('payment_method', 30)->nullable()->after('number_of_persons');
            $table->string('payment_reference', 80)->nullable()->after('payment_method');
            $table->decimal('amount_paid', 10, 2)->nullable()->after('payment_reference');

            // A booking now needs admin sign-off before it's considered final.
            $table->string('status', 20)->default('pending_confirmation')->after('amount_paid');
            $table->text('admin_notes')->nullable()->after('status');
            $table->timestamp('confirmed_at')->nullable()->after('admin_notes');
            $table->foreignId('confirmed_by')->nullable()->after('confirmed_at')
                ->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['confirmed_by']);
            $table->dropColumn([
                'payment_method',
                'payment_reference',
                'amount_paid',
                'status',
                'admin_notes',
                'confirmed_at',
                'confirmed_by',
            ]);
        });
    }
};
