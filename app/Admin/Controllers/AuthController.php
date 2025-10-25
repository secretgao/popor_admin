<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\AuthController as BaseAuthController;
use Illuminate\Http\Request;
use Encore\Admin\Facades\Admin;

class AuthController extends BaseAuthController
{
    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    protected function username()
    {
        return 'username';
    }

    /**
     * Handle a login request.
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function postLogin(Request $request)
    {
        $this->loginValidator($request->all())->validate();

        $username = $request->input('username');
        $password = $request->input('password');
        $remember = $request->get('remember', false);

        // 手动查找用户
        $user = \Encore\Admin\Auth\Database\Administrator::where('username', $username)->first();

        if ($user && \Hash::check($password, $user->password)) {
            // 手动登录用户
            $this->guard()->login($user, $remember);
            return $this->sendLoginResponse($request);
        }

        return back()->withInput()->withErrors([
            'username' => $this->getFailedLoginMessage(),
        ]);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendLoginResponse(Request $request)
    {
        admin_toastr(trans('admin.login_successful'));

        $request->session()->regenerate();

        return redirect()->intended($this->redirectPath());
    }
}
