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
            // التحقق إذا تم إرسال البريد الإلكتروني أو الهاتف
            if (isset($data['email']) && !isset($data['phone'])) {
                $user = User::where('email', $data['email'])->first();
            } elseif (!isset($data['email']) && isset($data['phone'])) {
                $user = User::where('phone', $data['phone'])->first();
            } else {
                throw new \Exception('يجب أن تحتوي البيانات إما على البريد الإلكتروني أو رقم الهاتف.');
            }

            // التحقق من صحة المستخدم وكلمة المرور
            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Invalid Credentials',
                ], 401);
            }

            // إنشاء التوكن للمستخدم
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

            // تحديد نوع المستخدم
            $userType = 'User'; // القيمة الافتراضية

            if ($user->type == 1) {
                $userType = 'Admin'; // إذا كان المستخدم أدمن
            } elseif ($user->vendor) { // التحقق إذا كان لديه سجل في vendor
                $userType = 'Vendor';
            }

            // إرجاع التوكن ونوع المستخدم
            return response()->json([
                'token' => $token,
                'user_type' => $userType,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred during login.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }


}
