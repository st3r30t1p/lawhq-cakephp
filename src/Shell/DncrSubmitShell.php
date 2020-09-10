<?php
namespace App\Shell;

use App\Service\DoNotCallService;
use Cake\Console\Shell;

class DncrSubmitShell extends Shell
{
    public function main()
    {

        $result = (new DoNotCallService())->sendPhoneToRegister();

        return $this->out($result);
    }
}
