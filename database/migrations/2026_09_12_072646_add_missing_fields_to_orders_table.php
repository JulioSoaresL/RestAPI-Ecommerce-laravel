<?php

use App\Models\Customer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->foreignIdFor(Customer::class)
                ->after('tenant_id')
                ->constrained('customers')
                ->cascadeOnDelete();

            $table->json('shipping_address')
                ->nullable()
                ->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['customer_id', 'shipping_address']);

            $table->foreignId('user_id')
                ->after('tenant_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }
};
