<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;


class AuthController extends Controller
{
    /**
     * Show the login page (GET method).
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect('/management/employee-management'); 
        }
        
        return view('authentification.login');
    }

    /**
     * Show the registration page (GET method).
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect('/management/employee-management'); 
        }
        return view('authentification.register');
    }

    /**
     * Handle user registration (POST method).
     */
    /**
 * Handle user registration (POST method).
 */
public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|regex:/^[\w\.-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,6}$/|unique:users',
        'password' => 'required|min:8|confirmed',
        'company_name' => 'required|string|max:255',
    ]);

    // Create the user
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'company_name' => $request->company_name,
        
    ]);

    // Log the user in and store session data
    return redirect('/login'); 
    session(['user_id' => $user->id, 'user_name' => $user->name, 'user_email' => $user->email]);

    return redirect('/dashboard')->with('success', 'Registration successful! You are now logged in.');
}

    /**
     * Handle user login (POST method).
     */
    /**
 * Handle user login (POST method).
 */
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email|regex:/^[\w\.-]+@[a-zA-Z0-9\.-]+\.[a-zA-Z]{2,6}$/',
        'password' => 'required',
    ]);

    if (!Auth::attempt($request->only('email', 'password'))) {
        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    $request->session()->regenerate();

    return redirect()->intended('/management/employee-management'); // Redirect to intended page or default
}
    
    /**
     * Handle user logout (POST method).
     */
    /**
 * Handle user logout (POST method).
 */
public function logout(Request $request)
{ 
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/login');
}



    public function showForgotPassword()
    {
        return view('authentification.forgotpassword');
    }
    
    public function handleForgotPassword(Request $request)
    {
        // Validate the email
        $request->validate(['email' => 'required|email|exists:users,email']);
        
        // Attempt to send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );
        
        // Check the status and respond accordingly
        if ($status === Password::RESET_LINK_SENT) {
            // Render the password link confirmation view
            return view('authentification.passwordlinkconfirmation', [
                'email' => $request->email, // Pass the email to the view
            ]);
        }
        
        // If there was an issue, return an error
        return redirect()->back()->withErrors(['email' => __($status)]);
    }
    
    
    public function showResetLinkConfirmation($email)
{
    return view('authentification.passwordlinkconfirmation', ['email' => $email]);
}
    public function showResetForm($token)
{
    return view('authentification.passwordreset', ['token' => $token]);
}

public function updateNewPassword(Request $request)
{
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => [
            'required',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/', // At least one uppercase, one lowercase, and one number
        ],
    ]);

    $user = User::where('email', $request->email)->first();

    if ($user) {
        try {
            $user->forceFill([
                'password' => Hash::make($request->password),
            ])->save();

            return view('authentification.passwordresetsuccess')->with('status', __('Your password has been reset successfully.'));
        } catch (\Exception $e) {
            return back()->withErrors(['email' => __('An error occurred while updating the password. Please try again.')]);
        }
    }

    return back()->withErrors(['email' => __('User not found. Please check the email address.')]);
}




public function showResetSuccess()
{
    return view('authentification.passwordlinkconfirmation');
}

public function update(Request $request)
{ 
    $user = Auth::user();

    // Validate the request
    $validated = $request->validate([
        'email' => 'sometimes|required|email|unique:users,email,' . $user->id, 
        'profile_image' => 'nullable|mimes:jpg,jpeg,png,bmp,tiff|max:4096',
        'company_name' => 'nullable|string|max:255',
        'contactNumber' => 'nullable|string|max:255', 
        
    ]);

    // Check if the user provided a new profile image
    if ($request->hasFile('profile_image')) {
        if ($user->profile_image && Storage::exists($user->profile_image)) {
            Storage::delete($user->profile_image);
        }

        // Store new profile image
        $profileImagePath = $request->file('profile_image')->store('profile-images', 'public');
        $user->profile_image = $profileImagePath; 
    }

    // Update the user's attributes only if they are present in the validated data
    if (isset($validated['email'])) {
        $user->email = $validated['email']; 
    }

    if (isset($validated['company_name'])) {
        $user->company_name = $validated['company_name']; 
    }

    if (array_key_exists('contactNumber', $validated)) {
        $user->contactNumber = $validated['contactNumber']; 
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully.');
}

public function deleteAccount(Request $request)
{
    // Ensure the user is authenticated
    $user = Auth::user();

    if ($user) {
        // Log out the user
        Auth::logout();

        // Delete the user record from the database
        $user->delete();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page with a success message
        return redirect('/register')->with('success', 'Your account has been deleted successfully.');
    }

    return redirect('/login')->withErrors(['error' => 'Unable to delete account.']);
}

public function updatePassword(Request $request)
{
    $request->validate([
        'password' => 'required',
        'new_password' => 'required|min:8'
    ]);

    $user = Auth::user();
    
    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'Current password is incorrect']);
    }

    try {
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        Auth::logout(); // Log out the user after password change for security
        return redirect('/login')->with('success', 'Password updated successfully. Please log in with your new password.');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => 'An error occurred while updating the password.']);
    }
}
}