<?php

namespace App\Http\Controllers;

use App\Invite;
use App\Notifications\Invite as InviteNotification;
use App\User;
use Illuminate\Http\Request;
use Notification;
use Str;
use URL;
use Validator;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',['except' => ['accept_invite']]);
    }

    public function index()
    {
        $users = User::all();
        return view('pages.users.index', ['users' => $users]);
    }

    public function invite_view()
    {
        return view('pages.users.invite');
    }

    public function invite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email'
        ]);
        $validator->after(function ($validator) use ($request) {
            if (Invite::where('email', $request->input('email'))->exists()) {
                $validator->errors()->add('email', 'There exists an invite with this email!');
            }
        });

        if ($validator->fails()) {
            return redirect(route('invite_view'))
                ->withErrors($validator)
                ->withInput();
        }
        do {
            $token = Str::random(50);
        } while (Invite::where('token', $token)->first());
        Invite:: create([
            'email' => $request->input('email'),
            'token' => $token
        ]);
        $url = URL::temporarySignedRoute(
            'accept', now()->addMinutes(30), ['token' => $token]
        );

        Notification::route('mail', $request->input('email'))
            ->notify(new InviteNotification($url));
        return redirect()->back()->with('success','Invitation sent successfully');

    }

    public function accept_invite($token)
    {
        $invite= Invite::where('token', $token)->first();
        return view('auth.register',['invite'=>$invite]);

    }
}
