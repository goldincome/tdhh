<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->addSlugColumns();
        $this->addPropertyIndexes();
    }

    /**
     * Add the slug and area_slug columns.
     *
     * Both are nullable so the migration is safe to run against a live table
     * that still contains rows without a slug. The seo:backfill-slugs command
     * fills them afterwards.
     *
     * 191 characters is used deliberately: at utf8mb4 that is 764 bytes, which
     * fits inside the 767 byte index key limit of the oldest InnoDB row
     * formats as well as the 3072 byte limit of modern ones. A varchar(255)
     * unique index would be 1020 bytes and would fail to create on an older
     * server.
     *
     * @return void
     */
    protected function addSlugColumns(): void
    {
        if (Schema::hasColumn('properties', 'slug') && Schema::hasColumn('properties', 'area_slug')) {
            return;
        }

        Schema::table('properties', function (Blueprint $table) {
            if (! Schema::hasColumn('properties', 'slug')) {
                $table->string('slug', 191)->nullable()->after('name');
            }

            if (! Schema::hasColumn('properties', 'area_slug')) {
                $table->string('area_slug', 191)->nullable()->after('slug');
            }
        });
    }

    /**
     * Add the missing indexes on the properties table.
     *
     * The table previously carried only its PRIMARY key on `reference`.
     * `status_id` is filtered on every public listing query and `id` is the
     * foreign key target used by the media and review relations, so both
     * were unindexed full table scans.
     *
     * @return void
     */
    protected function addPropertyIndexes(): void
    {
        $indexes = $this->existingIndexes();

        if (! in_array('properties_slug_unique', $indexes, true)) {
            Schema::table('properties', function (Blueprint $table) {
                $table->unique('slug', 'properties_slug_unique');
            });
        }

        if (! in_array('properties_area_slug_index', $indexes, true)) {
            Schema::table('properties', function (Blueprint $table) {
                $table->index('area_slug', 'properties_area_slug_index');
            });
        }

        if (! in_array('properties_status_id_index', $indexes, true)) {
            Schema::table('properties', function (Blueprint $table) {
                $table->index('status_id', 'properties_status_id_index');
            });
        }

        if (! in_array('properties_id_index', $indexes, true)) {
            Schema::table('properties', function (Blueprint $table) {
                $table->index('id', 'properties_id_index');
            });
        }
    }

    /**
     * The list of index names that already exist on the properties table.
     *
     * Read from information_schema rather than through the Doctrine schema
     * manager, so the migration does not depend on doctrine/dbal being
     * installed in the production environment.
     *
     * @return array<int, string>
     */
    protected function existingIndexes(): array
    {
        try {
            $database = DB::connection()->getDatabaseName();

            $rows = DB::select(
                'SELECT DISTINCT INDEX_NAME FROM information_schema.STATISTICS
                 WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?',
                [$database, 'properties']
            );

            return array_map(fn ($row) => $row->INDEX_NAME, $rows);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('properties', function (Blueprint $table) {
            $indexes = $this->existingIndexes();

            if (in_array('properties_id_index', $indexes, true)) {
                $table->dropIndex('properties_id_index');
            }

            if (in_array('properties_status_id_index', $indexes, true)) {
                $table->dropIndex('properties_status_id_index');
            }

            if (in_array('properties_area_slug_index', $indexes, true)) {
                $table->dropIndex('properties_area_slug_index');
            }

            if (in_array('properties_slug_unique', $indexes, true)) {
                $table->dropUnique('properties_slug_unique');
            }

            if (Schema::hasColumn('properties', 'area_slug')) {
                $table->dropColumn('area_slug');
            }

            if (Schema::hasColumn('properties', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
