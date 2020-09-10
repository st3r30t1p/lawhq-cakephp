<?php

use Migrations\AbstractMigration;

/**
 * Class CreateTemplates
 */
class CreateTemplates extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('templates');
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('google_doc_id', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
//        $table->addColumn('parameters', 'json', [
//            'default' => null,
//            'null' => false,
//        ]);
        $table->addColumn('created', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->create();
    }
}
