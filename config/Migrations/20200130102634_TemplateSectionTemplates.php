<?php
use Migrations\AbstractMigration;

class TemplateSectionTemplates extends AbstractMigration
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
        $table = $this->table('template_section_templates');
        $table->addColumn('template_id', 'integer', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addColumn('section_template_id', 'integer', [
            'default' => 0,
            'null' => false,
        ]);
        $table->addIndex(['template_id'])
        ->addForeignKey('template_id', 'templates', 'id');
        $table->addIndex(['section_template_id'])
            ->addForeignKey('section_template_id', 'section_templates', 'id');
        $table->create();
    }
}
