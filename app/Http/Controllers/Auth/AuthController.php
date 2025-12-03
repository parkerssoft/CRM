<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function index()
    {
        $user_id =  Auth::id();
        if (Auth::user()) {
            return redirect('/dashboard');
        } else {
            return view('Auth.login');
        }
    }

    public function Login(Request $request)
    {

        if (!empty($request)) {
            $email = $request->email;
            $password = $request->password;


            if (!empty($email) && !empty($password)) {

                // Determine the subdomain and user type
                $subdomain = explode('.', $request->getHost())[0];
                switch ($subdomain) {
                    case 'admin':
                        $type = ['admin', 'staff']; // Search for both admin and staff types
                        break;
                    case 'parker':
                        $type = ['admin', 'staff','channel','sales'];
                        break;
                    case 'partner':
                        $type = ['channel'];
                        break;
                    case 'sales-team':
                        $type = ['sales'];
                        break;
                    default:
                        $type = ['admin', 'staff','channel','sales'];
                        break;
                }
                $userdata = array(
                    'email' => $email,
                    'password' => $password,
                );

                if (Auth::attempt($userdata)) {
                    $user = Auth::user();

                    if (in_array($user->user_type, $type)) {
                        if ($request->has('remember') == null) {
                            setcookie('email', $email, 100);
                            setcookie('password', $password, 100);
                        } else {

                            setcookie('email', $email, time() + 606024100);
                            setcookie('password', $password, time() + 606024100);
                        }
                        session('Login', true);
                        flash()
                            ->success('Logged In successfully.')
                            ->flash();
                        return redirect('/application');
                    } else {
                        Session::flush();
                        Auth::logout();
                        flash()
                            ->error('Account does not exists.')
                            ->flash();
                        return redirect('/');
                    }
                } else {
                    return redirect()->back()->with('error', "Invalid Credential");
                }
            } else {
                return redirect()->back()->with('error', "Invalid Request");
            }
        } else {
            return redirect()->back()->with('error', "Invalid Request");
        }
        return view("Auth.Login");
    }


    public function Logout()
    {
        Session::flush();
        flash()
            ->success('Logged Out successfully.')
            ->flash();
        return redirect('/');
    }
}
