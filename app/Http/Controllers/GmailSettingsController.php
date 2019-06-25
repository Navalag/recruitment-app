<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use App\Settings;
use Illuminate\Support\Facades\Redirect;
use Exception;

class GmailSettingsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show gmail oauth view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $settings = Settings::where('user_id', auth()->id())->first();

        return view('settings.index', compact('settings'));
    }

    /**
     * Make oauth request to Gmail api.
     *
     * @return Redirect
     */
    public function auth()
    {
        return LaravelGmail::redirect();
    }

    /**
     * Save token.
     *
     * @return Redirect
     */
    public function createToken()
    {
        LaravelGmail::makeToken();

        Settings::updateOrCreate(['user_id' => auth()->id()], [
            'user_id' => auth()->id(),
            'sign_in_with_google' => true
        ]);

        return redirect('/applicant');
    }

    /**
     * Logout from gmail.
     *
     * @return Redirect
     *
     * @throws Exception
     */
    public function logout()
    {
        LaravelGmail::logout(); //It returns exception if fails

        Settings::where('user_id', auth()->id())->first()->update(['sign_in_with_google' => false]);

        return redirect()->to('/applicant');
    }
}
