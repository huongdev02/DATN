<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ship_address;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipAddressController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id(); // Lấy ID người dùng hiện tại, nếu có
        $query = Ship_address::query();

        // Nếu người dùng đã đăng nhập, chỉ lấy địa chỉ của người dùng đó
        if ($user_id) {
            $query->where('user_id', $user_id);
        }

        // Lấy danh sách địa chỉ
        $shipAddresses = $query->get();

        return response()->json($shipAddresses);
    }
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'ship_address' => 'required|string|max:255',
        ]);

        $shipAddress = Ship_address::create([
            'user_id' => Auth::id(),
            'recipient_name' => $validatedData['recipient_name'],
            'email' => $validatedData['email'],
            'phone_number' => $validatedData['phone_number'],
            'ship_address' => $validatedData['ship_address'],
        ]);

        return response()->json(['id' => $shipAddress->id], 201);
    }
}
