<?php

namespace App\Http\Controllers;

use App\Traits\Checklist;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Checklist;
}
