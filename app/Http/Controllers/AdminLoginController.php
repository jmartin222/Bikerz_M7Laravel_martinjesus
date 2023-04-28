<?php
    namespace App\Http\Controllers;


    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Validator;
    use Illuminate\Validation\Rule;
    use Illuminate\Routing\Controller as BaseController;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Database\Query\Builder;

    class AdminLoginController extends BaseController
    {
        public function showLoginForm()
        {
            return view('backend.admin_login');
        }

        public function login(Request $request)
        {
            $validator = Validator::make($request->all(),[
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $validator->setAttributeNames([
                'email' => 'mail',
                'password' => 'contrasenya',
            ]);

            $return_val;

            if ($validator->fails()) {
                $return_val = redirect()->back()->withErrors($validator)->withInput();
            }

            else if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
                $return_val = redirect()->intended(route('curses.index'));
            }

            else{
                $return_val = redirect()->back()->withErrors(['email' => 'Estas credenciales no coinciden con nuestros registros.'])->withInput();
            }

            return $return_val;
        }

        public function logout(Request $request)
        {
            Auth::guard('admin')->logout();

            return redirect('/admin');
        }
    }
?>