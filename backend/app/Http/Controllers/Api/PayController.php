<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class PayController extends Controller
{
    public function index()
    {
        $payments = Payment::all();
        return response()->json($payments);
    }

    public function store(Request $request)
    {
        try {
            $validateData = $request->validate([
                'user_id' => 'required|integer',
                'order_id' => 'required|integer',
                'amount' => 'required|numeric',
                'payment_method' => 'required|string',
                'status' => 'required|string',
            ]);

            $payment = Payment::create($validateData);
            return response()->json($payment, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error creating payment: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            return response()->json($payment);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error retrieving payment: ' . $e->getMessage()], 500);
        }
    }
    
    public function update(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);
    
            // Validate input data
            $validateData = $request->validate([
                'user_id' => 'required|integer',
                'order_id' => 'required|integer',
                'amount' => 'required|numeric',
                'payment_method' => 'required|string',
                'status' => 'required|string',
            ]);
    
            // Update payment details
            $payment->update($validateData);
    
            // Return success response
            return response()->json($payment, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            Log::error('Error updating payment: ' . $e->getMessage()); // Log the error
            return response()->json(['message' => 'Error updating payment: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();
            return response()->json(['message' => 'Payment deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Payment not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting payment: ' . $e->getMessage()], 500);
        }
    }
}
