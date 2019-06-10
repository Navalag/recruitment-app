<?php

namespace App\Http\Controllers;

use Dacastro4\LaravelGmail\Facade\LaravelGmail;
use Illuminate\Http\Request;
use App\Settings;

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

    public function index()
    {
        $settings = Settings::where('user_id', auth()->id())->first();

        return view('settings.index', compact('settings'));
    }

    public function auth()
    {
        return LaravelGmail::redirect();
    }

    public function createToken()
    {
        LaravelGmail::makeToken();

        Settings::updateOrCreate(['user_id' => auth()->id()], [
            'user_id' => auth()->id(),
            'sign_in_with_google' => true
        ]);

        return redirect('/applicant');
    }

    public function logout()
    {
        LaravelGmail::logout(); //It returns exception if fails

        Settings::where('user_id', auth()->id())->first()->update(['sign_in_with_google' => false]);

        return redirect()->to('/applicant');
    }
}
