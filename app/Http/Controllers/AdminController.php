<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    public function adminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $verificationCode = random_int(100000, 999999);
            session(['verification_code' => $verificationCode, 'user_id' => $user->id]);
            // Here you would typically send the verification code via email or SMS
            Mail::to($user->email)->send(new VerificationCodeMail($verificationCode));
            Auth::logout(); // Log out the user until they verify
            return redirect()->route('custom.verification.form')->with('status', 'Verification code sent! Please check your email.');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showVerificationForm(Request $request)
    {
        return view('auth.verify');
    }
    public function verificationVerify(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $storedCode = session('verification_code');
        $userId = session('user_id');

        if ($request->input('code') == $storedCode) {
            // Verification successful, log the user in
            Auth::loginUsingId($userId);
            // Clear the verification code from the session
            $request->session()->forget(['verification_code', 'user_id']);
            return redirect()->intended('/dashboard');
        } else {
            return back()->withErrors(['code' => 'Invalid verification code.'])->onlyInput('code');
        }
    }
    public function adminProfile()
    {
        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('admin.admin_profile', compact('profileData'));
    }

    public function profileStore(Request $request)
    {
        $id = Auth::user()->id;
        $data = User::find($id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->address = $request->address;

        $oldPhotoPath = $data->photo;
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/user_images'), $fileName);
            $data->photo = $fileName;

            if ($oldPhotoPath && $oldPhotoPath !== $fileName) {
                $this->deleteOldImage($oldPhotoPath);
            }
        }
        $data->save();
        toastr()->success('Data has been update successfully!');
        return redirect()->back();
    }
    private function deleteOldImage($fileName)
    {
        $filePath = public_path('upload/user_images/' . $fileName);

        if (file_exists($filePath)) {
            @unlink($filePath); // delete the old image
        }
    }

    public function adminPasswordUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => "required",
            'new_password' => "required|confirmed"
        ]);
        if (!Hash::check($request->old_password, $user->password)) {
            toastr()->error('Old Password dose not match');
            return back();
        }
        User::whereId($user->id)->update(
            [
                'password' => Hash::make($request->new_password),
            ]
        );
        Auth::logout();
        toastr()->success('Password updated successfuly! Please Login with new password!');
        return redirect()->route('login');
    }
}
