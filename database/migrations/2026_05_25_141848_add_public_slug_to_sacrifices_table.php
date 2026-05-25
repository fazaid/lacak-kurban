<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sacrifices', function (Blueprint $table) {
            $table->string('public_slug')->nullable()->unique()->after('reference_code');
            $table->string('donor_email_hash', 64)->nullable()->index()->after('public_slug');
            $table->ipAddress('last_accessed_ip')->nullable()->after('donor_email_hash');
            $table->timestamp('last_accessed_at')->nullable()->after('last_accessed_ip');
            $table->unsignedInteger('access_count')->default(0)->after('last_accessed_at');
        });

        // Back-fill slugs and email hashes for existing records
        $existing = DB::table('sacrifices')->select('id', 'donor_email')->get();
        foreach ($existing as $row) {
            $slug = null;
            do {
                $slug = 'sac_' . bin2hex(random_bytes(14));
            } while (DB::table('sacrifices')->where('public_slug', $slug)->exists());

            $update = ['public_slug' => $slug];
            if ($row->donor_email) {
                $update['donor_email_hash'] = hash('sha256', strtolower(trim($row->donor_email)));
            }

            DB::table('sacrifices')->where('id', $row->id)->update($update);
        }

        // Now make public_slug NOT NULL with its unique index in place
        Schema::table('sacrifices', function (Blueprint $table) {
            $table->string('public_slug')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('sacrifices', function (Blueprint $table) {
            $table->dropIndex(['donor_email_hash']);
            $table->dropColumn(['public_slug', 'donor_email_hash', 'last_accessed_ip', 'last_accessed_at', 'access_count']);
        });
    }
};
