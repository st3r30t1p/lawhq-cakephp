<?php

use Migrations\AbstractSeed;

/**
 * Templates seed.
 */
class TemplatesSeed extends AbstractSeed
{
    public function run()
    {
        $this->table('templates')->insert([
            'name'          => 'DefaultTemplate',
            'google_doc_id' => '1Nvy-ccmWN6hrNgqtyQolq5J7P6ipw7p3QsiMFB-M0Zo',
            'parameters'    => '"{\"names\":\"array\",\"email\":\"string\"}"'
        ])->save();
    }
}
