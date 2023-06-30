<?php
namespace App\Http\Controllers;

use App\Models\Users;
use Illuminate\Http\Request;

session_start();

class UsersController extends Controller
{
    // login the user
    public function getUser(Request $request)
    {
        $username = $request->get('username');
        $password = $request->get('password');

        if (!empty($username) && !empty($password)) {
            $user = Users::where('email', $username)->first();
            if (!empty($user)) {
                $user = $user->toArray();
                if (password_verify($password, $user['password'])) {
                    $_SESSION['email'] = $user['email'];
                    return response()->json([
                        'bool' => "true",
                        'message' => "login successful",
                    ]);
                } else {
                    return response()->json([
                        'bool' => "false",
                        'message' => "Email or Password are incorrect",
                    ]);
                }
            } else {
                return response()->json([
                    'bool' => "false",
                    'message' => "Email or Password are incorrect",
                ]);
            }
        }else{
			return response()->json([
				'bool' => "false",
				'message' => "Email or Password fields are mandatory",
			]);
		}
    }

    // CHECK is the user is logged in
    public function checkUser(Request $request)
    {
        if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
            return response()->json([
                'bool' => "true",
            ]);
        } else {
            return response()->json([
                'bool' => "false",
            ]);
        }
    }
}
