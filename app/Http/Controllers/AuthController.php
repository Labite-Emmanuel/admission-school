<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session; // <= important
use App\Models\User;
use App\Models\AcademicYear;
use App\Models\Classe;
use App\Models\StudentDetail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // Page de connexion
    public function showLoginForm()
    {
        return view('login-2');
    }
    
    private function redirectToDashboard($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'manager':
                return redirect()->intended('/manager/dashboard');
            case 'teacher':
                return redirect()->intended('/teacher/dashboard');
            case 'student':
                return redirect()->intended('/student/dashboard');
            default:
                return redirect()->intended('/dashboard');
        }
    }


    public function login(Request $request)
    {
        // Validation des données
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string|min:6'
        ], [
            'email.required' => 'The email or username field is required',
            'password.required' => 'The password field is required',
            'password.min' => 'The password must be at least 6 characters'
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Vérification utilisateur
        $user = User::where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('username', $request->email);
        })->first();

        if (!$user) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No account found with this email or username',
                ], 422);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'No account found with this email or username');
        }

        // Vérification du mot de passe (Hash pour nouveaux comptes, base64 pour anciens)
        $passwordValid = Hash::check($request->password, $user->password)
            || $user->password === base64_encode($request->password);
        if (!$passwordValid) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'The password is incorrect',
                ], 422);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'The password is incorrect');
        }

        $session_data = [
            'user' => [
                'id' => $user->id_us,
                'id_autority' => $user->id_autority,
                'first_name' => $user->first_name,
                'email' => $user->email,
                'username' => $user->username,
                'nature' => $user->nature,
                'statut' => $user->statut,
                'role' => $user->role,
                'etat' => $user->etat,
                'code_academic' => $user->code_academic,
                'code_parent' => $user->code_parent,
                'end_registration' => $user->end_registration,
                'academic_year' => $user->academic_year,
            ]
        ];

        // Stocker dans la session
        Session::put($session_data);

            // Logique de redirection
            switch (true) {
                case ($user->statut == 900 && $user->etat == 1):
                    switch ($user->role) {
                        case 11:
                            $redirectUrl = '/level-supervisor';
                            break;
                        case 15:
                            $redirectUrl = '/stock-management';
                            break;
                        case 14:
                            $redirectUrl = '/driver-space';
                            break;
                        case 12:
                            $redirectUrl = '/driver-space/dash-manager';
                            break;
                        case 13:
                            $redirectUrl = '/services-coordinator';
                            break;
                        case 16:
                            $redirectUrl = '/index';
                            break;
                        default:
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Account disabled !! Please contact the administrator'
                            ], 403);
                    }
                    break;

                case ($user->statut == 1 && $user->etat == 1):
                    $redirectUrl = '/index';
                    break;

                case ($user->statut == 202 && $user->etat == 1):
                    $redirectUrl = '/teacher/dash';
                    break;

                case ($user->statut == 303 && $user->etat == 1):
                    $redirectUrl = '/parents?code=' . base64_encode($user->code_parent);
                    break;

                case ($user->statut == 304 && $user->etat == 1):
                    $redirectUrl = '/myschoolathome?code=' . base64_encode($user->code_academic) . '&class=' . base64_encode($user->class);
                    break;

                case ($user->statut == 909 && $user->etat == 1):
                    if ($user->end_registration == 'no' || $user->end_registration == 'process') {
                        $redirectUrl = '/home-admission';
                        
                    } elseif ($user->end_registration == 'end') {
                        $redirectUrl = '/home-admission';
                    }
                    break;

                default:
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Account disabled !! Please contact the administrator',
                        ], 403);
                    }

                    return redirect()
                        ->back()
                        ->withInput()
                        ->with('error', 'Account disabled !! Please contact the administrator');
            }

            // Redirection vers la page demandée si l'utilisateur venait d'être renvoyé vers le login
            if (session()->has('intended')) {
                $intended = session('intended');
                if (is_string($intended) && str_starts_with($intended, '/') && !str_starts_with($intended, '//')) {
                    $redirectUrl = $intended;
                }
                session()->forget('intended');
            }

            // Connexion réussie
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Connection successful',
                    'session_data' => $session_data,
                    'users' => $user,
                    'redirect' => $redirectUrl
                ]);
            }

            return redirect()->to($redirectUrl)->with('success', 'Connection successful');

    }


    // Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Vous avez été déconnecté avec succès');
    }

    public function showRegisterForm()
    {
        return view('register-2');
    }

    public function showForgotPasswordForm()
    {
        return view('forgot-password');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be valid.',
            'email.exists' => 'No account found with this email.',
            'password.required' => 'The new password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }

            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $user = User::where('email', $request->email)->first();
        $user->password = $request->password;
        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Password updated successfully.',
                'redirect' => url('login'),
            ]);
        }

        return redirect()
            ->route('login')
            ->with('success', 'Password updated successfully. You can now sign in.');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be valid.',
            'email.unique' => 'This email is already used.',
            'username.required' => 'The username field is required.',
            'username.unique' => 'This username is already taken.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 422);
            }
            return redirect()->route('register')->withErrors($validator)->withInput();
        }
        
        try {
            $academicYearModel = AcademicYear::where('etat', true)->first();
            $academicYear = $academicYearModel?->year ?? now()->year;

            $user = User::create([
                'first_name' => $request->name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => $request->password,
                'statut' => '909',
                'etat' => '1',
                'role' => '909',
                'end_registration' => 'no',
                'id_autority' => 0,
                'login' => '0',
                'is_connect' => false,
                'hour_connect' => now(),
                'over_connect' => null,
                'open_school' => false,
                'academic_year' =>  $academicYear,
                'date_save' => now(),
            ]);

            $data = ['success' => 'Account created successfully. You can now login.'];

            $data['classes'] = Classe::where('aca_year', $academicYear)->get();

            if (\Schema::hasTable('admission_step')) {
                $data['admilists'] = StudentDetail::query()
                    ->join('admission_step', 'admission_step.code_stud', '=', 'StudentDetails.code_student')
                    ->where('StudentDetails.id_userS', $user->id_us)
                    ->where('admission_step.academicyear', $academicYear)
                    ->select('StudentDetails.*', 'admission_step.*')
                    ->get();
            } else {
                $data['admilists'] = StudentDetail::where('id_userS', $user->id_us)->get();
            }

            $data['users'] = $user;
            $data['acayear'] = $academicYearModel ?: (object) ['year' => $academicYear];

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Account created successfully.',
                    'redirect' => url('login'),
                ]);
            }

            return redirect()->to('login')->with($data);
        } catch (\Exception $e) {
            dd($e);
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'An error occurred while creating the account.',
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the account. Please try again.');
        }
    }
}
