<?php

namespace App\Http\Controllers\Registration;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\registartion\RegisterUser ; // Ensure the namespace is correct
use App\Services\registartion\register; // Ensure the namespace is correct
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache; // Import Cache facade
use Illuminate\Support\Str;
use Twilio\Rest\Client;
use App\Helpers\OtpHelper;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(register $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle the registration of a new user.
     *
     * @param RegisterUser   $request
     * @return JsonResponse
     */
    public function register(RegisterUser $request): JsonResponse
    {
        $validatedData = $request->validated();

        // Pass the modified request data to the service
        $user = $this->userService->register($validatedData);


        return response()->json([
            'message' => 'User  registered successfully',
            'user' => $user,
        ], 201);
    }




}
