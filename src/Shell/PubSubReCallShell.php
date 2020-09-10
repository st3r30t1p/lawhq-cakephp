<?php
namespace App\Shell;
use App\Service\GoogleService;
use Cake\Console\Shell;

class PubSubReCallShell extends Shell
{
    public function main()
    {
        //need re-call every 7 days; bin/cake pub_sub_re_call
        $result = GoogleService::notification('watch');
        return $this->out(json_encode($result));
    }
}