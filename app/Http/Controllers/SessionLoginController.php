<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Session;
use App\UserAccess;
use App\Http\Requests;

class SessionLoginController extends Controller
{
    public function login($employee_id, $employee_no, $full_name, $section)
    {
        $credentials = [
            'employee_id' => base64_decode(urldecode($employee_id)),
            'employee_no' => base64_decode(urldecode($employee_no)),
            'full_name' => base64_decode(urldecode($full_name)),
            'section' => base64_decode(urldecode($section))
        ];

        return $this->check_access($credentials);
    }

    public function check_access($credentials)
    {
        $user_access = UserAccess::select('et.*')
            ->leftJoin('email_tab as et', 'et.employee_id', '=', 'user_access_tab.employee_id')
            ->where([
                'system_id'      => config('app.system_id'),
                'user_type_id'   => 2,
                'et.employee_id' => $credentials['employee_id']
            ])
            ->exists();

        if (!$user_access) return redirect()->away('http://portal.isuzuphil.com/ipc_central/main_home.php');

        session($credentials);

        if (Session()->has(
            [
                'section', 
                'full_name', 
                'employee_no', 
                'employee_id'
            ]
        )) {
            Session()->regenerate();
            return redirect()->route('training_requests');
        }
        else {
            return redirect()->away('http://portal.isuzuphil.com/ipc_central/main_home.php');
        }
    }

    public function logout()
    {
        Session::flush();
        return redirect()->away('http://portal.isuzuphil.com/ipc_central/php_processors/proc_logout.php');
    }
}
