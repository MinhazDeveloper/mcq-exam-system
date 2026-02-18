<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Exam;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{   
    public function userAll(Request $request)
    {   
        $user_all = User::select([
            'id', 
            'name', 
            'email', 
            'role', 
            'email_verified_at', 
            'last_login_at', 
            'created_at'
        ])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Hello admin',
            'data' => $user_all,
        ]);
    }
    //store user
    public function storeUser(Request $request)
    {
        // ১. ডাটা ভ্যালিডেশন
        $fields = $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string',
        ]);

        // ২. ইউজার তৈরি
        $user = User::create([
            'name'     => $fields['name'],
            'email'    => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role'     => $fields['role'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'data'    => $user
        ], 201);
    }
    //update user
    public function updateUser(Request $request, $id)
    {
        
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'role'  => 'required|string',
            // 'role' => 'nullable|string',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|min:6',
        ]);

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->role  = $request->role;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'data'    => $user
        ], 200);
    }
    public function dashboard()
    {
        return response()->json([
            'success' => true,
            'message' => 'Welcome admin',
        ]);
    }
    // dashboard stats
    public function getStats()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalExams = Exam::count();
        // $totalAttempts = Attempt::count();
        // $totalRevenue = Payment::where('status', 'success')->sum('amount');

        // গত ৭ দিনের রেজিস্ট্রেশন ডাটা সংগ্রহ
        $registrations = User::where('role', 'student')
            ->where('created_at', '>=', now()->subDays(6)) // আজসহ মোট ৭ দিন
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // চার্টের জন্য ডাটা ফরম্যাট করা (যাতে কোনো দিন গ্যাপ থাকলে ০ দেখায়)
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dayName = now()->subDays($i)->format('D');
            
            $found = $registrations->where('date', $date)->first();
            
            $chartData[] = [
                'day' => $dayName,
                'count' => $found ? $found->count : 0
            ];
        }    

        $examsToday = Exam::whereDate('updated_at', today())
            ->get()
            ->map(function($exam) {
                return [
                    'id' => $exam->id,
                    'title' => $exam->title,
                    'duration_minutes' => $exam->duration_minutes,
                    'total_marks' => $exam->total_marks ?? 0,
                    'time' => $exam->start_time 
                                ? Carbon::parse($exam->start_time)->format('h:i A') 
                                : null,
                ];

            });    

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($log) {
                return [
                    'user_name' => $log->user->name ?? 'N/A',
                    'action' => $log->action,
                    'ip_address' => $log->ip_address,
                    'created_at_human' => $log->created_at->diffForHumans(),
                    'status' => $log->status,
                ];
            });

        // ৩. ফাইনাল রেসপন্স
        return response()->json([
            "success" => true,
            "data" => [
                "total_students" => $totalStudents,
                // "students_trend" => $studentsTrend,
                "total_exams" => $totalExams,
                "registration_trend" => $chartData,
                "exams_today" => $examsToday,
                'recent_activities' => $recentActivities
                // "exams_trend" => 5, // এটিও একইভাবে ক্যালকুলেট করতে পারেন
                // "total_attempts" => $totalAttempts,
                // "total_revenue" => $totalRevenue
            ]
        ], 200);
    }
}
