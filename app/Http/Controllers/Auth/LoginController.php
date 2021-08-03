<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthorizedAccount;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')
            ->with(['hd' => 'ide.edu.ec'])
            ->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $social_user = Socialite::driver('google')->user();

        if ($user = User::where('email', $social_user->email)->first()) {
            return $this->authAndRedirect($user); // Login y redirección
        } else {
            $account = AuthorizedAccount::firstWhere('email', $social_user->email);

            if (!$account) {
                return redirect()->route('unauthorizedaccount');
            }
            // En caso de que no exista creamos un nuevo usuario con sus datos.
            $user = User::create([
                'name' => $social_user->name,
                'email' => $social_user->email,
                'provider' => 'google',
                'authorized_account_id' => $account->id,
            ]);

            return $this->authAndRedirect($user); // Login y redirección
        }
    }

    public function authAndRedirect($user)
    {
        Auth::login($user);

        return redirect()->to('/basicbookingscalendar');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->to('/');
    }

    public function displayUnauthorizedAccountMsg()
    {
        return view('unauthorizedaccount');
    }

    public function getUserInfo()
    {
        $userId = Auth::user()->id;

        return response()->json(User::with('authorizedAccount')->find($userId));
    }
}
