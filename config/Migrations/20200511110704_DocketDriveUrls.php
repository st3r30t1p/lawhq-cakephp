<?php
use Migrations\AbstractMigration;

class DocketDriveUrls extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('docket_drive_urls');
        $table->addColumn('docket_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('doc_name', 'string', [
            'default' => null,
            'limit' => 255,
            'null' => false,
        ]);
//        $table->addIndex(['docket_id'])
//            ->addForeignKey('docket_id', 'dockets', 'id');

        $table->create();
    }
}
