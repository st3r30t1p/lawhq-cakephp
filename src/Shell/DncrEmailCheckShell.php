<?php
namespace App\Shell;

use App\Service\DoNotCallService;
use Cake\Console\Shell;

class DncrEmailCheckShell extends Shell
{
    public function main()
    {

        $result = (new DoNotCallService())->dncrCheckEmail();

        return $this->out(json_encode($result));
    }
}
