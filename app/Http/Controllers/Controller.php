<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getDistrict($state_code)
    {
        $states = getState();
        foreach ($states as $state) {
            if ($state['state_code'] === $state_code) {
                $districts =  $state['districts'];
            }
        }

        $option ='';
        foreach($districts as $district ){
            $option.='<option>'.$district.'</option>';
        }


        return $option;
    }
}
