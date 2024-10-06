<?php

namespace App\Http\Controllers;

use App\Models\ProcurementRequest;
use App\Models\ProcurementItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcurementRequestController extends Controller
{
    // Create a procurement request with items
    public function createProcurementRequest(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Create a unique request code
        $requestCode = 'PR-' . strtoupper(uniqid());

        // Calculate total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['unit_price'];
        }

        // Create the procurement request
        $procurementRequest = ProcurementRequest::create([
            'request_code' => $requestCode,
            'user_id' => $request->user_id,
            'status' => 'pending',
            'total_amount' => $totalAmount,
        ]);

        // Create procurement items
        foreach ($request->items as $item) {
            ProcurementItem::create([
                'procurement_request_id' => $procurementRequest->id,
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        return response()->json(['message' => 'Procurement request created successfully', 'data' => $procurementRequest], 201);
    }

    // List all procurement requests
    public function listProcurementRequests()
    {
        $procurementRequests = ProcurementRequest::with('items')->get();
        return response()->json($procurementRequests);
    }

    // Approve a procurement request
    public function approveProcurementRequest($id)
    {
        // Find the procurement request by ID
        $procurementRequest = ProcurementRequest::find($id);

        if (!$procurementRequest) {
            return response()->json(['error' => 'Procurement request not found'], 404);
        }

        // Update the status to approved
        $procurementRequest->status = 'approved';
        $procurementRequest->save();

        return response()->json(['message' => 'Procurement request approved successfully', 'data' => $procurementRequest]);
    }

    // Reject a procurement request
    public function rejectProcurementRequest($id)
    {
        // Find the procurement request by ID
        $procurementRequest = ProcurementRequest::find($id);

        if (!$procurementRequest) {
            return response()->json(['error' => 'Procurement request not found'], 404);
        }

        // Update the status to rejected
        $procurementRequest->status = 'rejected';
        $procurementRequest->save();

        return response()->json(['message' => 'Procurement request rejected successfully', 'data' => $procurementRequest]);
    }
}

