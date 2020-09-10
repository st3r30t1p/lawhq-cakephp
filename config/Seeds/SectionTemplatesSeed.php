<?php

use Migrations\AbstractSeed;

/**
 * Templates seed.
 */
class SectionTemplatesSeed extends AbstractSeed
{
    public function run()
    {
        $this->table('section_templates')->insert([
            'name'          => 'SectionHeader',
            'google_doc_id' => '141t4UMIIvCmutoGpWk1Z-9z8510pWuJekksCXiFg6Z8'
        ])->save();
    }
}
