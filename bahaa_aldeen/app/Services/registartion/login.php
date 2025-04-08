<?php

namespace App\Services\registartion;

use App\Models\User;
use App\Models\Driver;
use App\Models\Provider_Product; // Import your ProviderProduct model
use App\Models\Provider_Service; // Import your ProviderService model
use Illuminate\Support\Facades\Hash;
class login
{
    /**
     * Register a new user and create related records based on user type.
     *
     * @param array $data
     * @return User
     */
    public function login(array $data)
    {
        try {
            // التحقق من إدخال البريد أو الهاتف
            if (isset($data['email']) && !isset($data['phone'])) {
                $user = User::where('email', $data['email'])->first();
            } elseif (!isset($data['email']) && isset($data['phone'])) {
                $user = User::where('phone', $data['phone'])->first();
            } else {
                return [
                    'error' => true,
                    'message' => 'يجب إدخال إما البريد الإلكتروني أو رقم الهاتف، وليس كلاهما.',
                    'code' => 400,
                ];
            }

            // التحقق من صحة كلمة المرور
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return [
                    'error' => true,
                    'message' => 'بيانات الاعتماد غير صحيحة',
                    'code' => 401,
                ];
            }

            // إنشاء التوكن
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

            // تحديد نوع المستخدم
            $userType = 'User';
            if ($user->type == 1) {
                $userType = 'Admin';
            } elseif ($user->employee->job->title == "BranchManager") {
                $userType = 'BranchManager';
            } // ... (باقي الشروط)

            return [
                'token' => $token,
                'user_type' => $userType,
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => 'حدث خطأ أثناء تسجيل الدخول: ' . $e->getMessage(),
                'code' => 500,
            ];
        }
    }


}
