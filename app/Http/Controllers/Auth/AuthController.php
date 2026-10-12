<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showRegister()
    {
        $classrooms = \App\Models\Classroom::orderBy('name')->get();
        return view('auth.register', compact('classrooms'));
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'role_type' => ['required', 'in:student,teacher'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            // Student class selection
            'classroom_id' => ['nullable', 'required_if:role_type,student'],
            'new_class_name' => ['nullable', 'string', 'max:100'],
            // Teacher multiple classes selection
            'teacher_class_ids' => ['nullable', 'array'],
            'teacher_class_ids.*' => ['exists:classrooms,id'],
            'teacher_new_class' => ['nullable', 'string', 'max:100'],
        ]);

        $classId = null;

        // If a new class was entered (e.g., student typed a new class name)
        if (!empty($data['new_class_name'])) {
            $createdClass = \App\Models\Classroom::firstOrCreate(
                ['name' => trim($data['new_class_name'])],
                ['code' => strtoupper(substr(trim($data['new_class_name']), 0, 6))]
            );
            $classId = $createdClass->id;
        } elseif (!empty($data['classroom_id']) && is_numeric($data['classroom_id'])) {
            $classId = $data['classroom_id'];
        }

        $assignedRole = $data['role_type'] === 'teacher' ? 'teacher' : 'student';

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $assignedRole,
            'phone' => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'classroom_id' => $assignedRole === 'student' ? $classId : null,
        ]);

        \Spatie\Permission\Models\Role::firstOrCreate(['name' => $assignedRole, 'guard_name' => 'web']);
        $user->syncRoles([$assignedRole]);

        if ($assignedRole === 'teacher') {
            // Attach selected teaching classes
            $assignedClasses = $data['teacher_class_ids'] ?? [];

            // If teacher typed a new class during registration
            if (!empty($data['teacher_new_class'])) {
                $newTClass = \App\Models\Classroom::firstOrCreate(
                    ['name' => trim($data['teacher_new_class'])],
                    ['created_by' => $user->id]
                );
                $assignedClasses[] = $newTClass->id;
            }

            if (!empty($assignedClasses)) {
                $user->taughtClasses()->sync(array_unique($assignedClasses));
            }
        }

        Auth::login($user);

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')->with('success', "Welcome {$user->name}, your teacher account is active.")
            : redirect()->route('dashboard')->with('success', "Welcome {$user->name}, your student account is active.");
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
