<?php

namespace App\Http\Controllers;

use App\Http\Requests\PendingTransaction\StorePendingTransactionRequest;
use App\Models\PendingTransaction;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendingTransactionController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();

        if (! $user->family_group_id) {
            return $this->errorResponse('User does not belong to a family group', 403);
        }

        $pendingTransactions = PendingTransaction::with(['assignedToUser', 'creatorUser'])
            ->where('family_group_id', $user->family_group_id)
            ->where('status', 'PENDING')
            ->orderBy('due_date', 'asc')
            ->get();

        return $this->successResponse($pendingTransactions, 'Pending assignments retrieved successfully');
    }

    public function store(StorePendingTransactionRequest $request)
    {
        $user = Auth::user();

        if (! $user->family_group_id) {
            return $this->errorResponse('User does not belong to a family group', 403);
        }

        $data = $request->validated();
        $data['family_group_id'] = $user->family_group_id;
        $data['creator_user_id'] = $user->id;
        $data['status'] = 'PENDING';

        $pendingTransaction = PendingTransaction::create($data);

        return $this->successResponse($pendingTransaction, 'Assignment created successfully', 201);
    }

    public function show(string $id)
    {
        $pendingTransaction = PendingTransaction::find($id);
        if (! $pendingTransaction) {
            return $this->errorResponse('Assignment not found', 404);
        }

        return $this->successResponse($pendingTransaction);
    }

    public function update(Request $request, string $id)
    {
        $pendingTransaction = PendingTransaction::find($id);

        if (! $pendingTransaction) {
            return $this->errorResponse('Assignment not found', 404);
        }

        if (Auth::user()->family_group_id !== $pendingTransaction->family_group_id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $pendingTransaction->update($request->all());

        return $this->successResponse($pendingTransaction, 'Assignment updated successfully');
    }

    public function destroy(string $id)
    {
        $pendingTransaction = PendingTransaction::find($id);

        if (! $pendingTransaction) {
            return $this->errorResponse('Assignment not found', 404);
        }

        if (Auth::user()->family_group_id !== $pendingTransaction->family_group_id) {
            return $this->errorResponse('Unauthorized', 403);
        }

        $pendingTransaction->delete();

        return $this->successResponse(null, 'Assignment deleted successfully');
    }
}
