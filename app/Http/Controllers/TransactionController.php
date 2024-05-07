<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
    public function index()
    {
        // Show all transactions and current balance
        $transactions = Transaction::all();
        // Calculate current balance for each user
        $users = User::with('transactions')->get();
        // Return transactions and balances
        return response()->json(['transactions' => $transactions, 'users' => $users]);
    }

    public function deposits()
    {
        // Show all deposited transactions
        $deposits = Transaction::where('transaction_type', 'deposit')->get();
        return response()->json(['deposits' => $deposits]);
    }

    public function deposit(Request $request)
    {
        // Accept user ID and amount, update user's balance by adding deposited amount
        $user = User::findOrFail($request->input('user_id'));
        $amount = $request->input('amount');
        // Update user's balance
        $user->balance += $amount;
        $user->save();
        // Record the deposit transaction
        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'deposit',
            'amount' => $amount,
            'fee' => 0, // No fee for deposit
            'date' => now()
        ]);
        return response()->json(['message' => 'Deposit successful']);
    }

    public function withdrawals()
    {
        // Show all withdrawal transactions
        $withdrawals = Transaction::where('transaction_type', 'withdrawal')->get();
        return response()->json(['withdrawals' => $withdrawals]);
    }

    public function withdraw(Request $request)
    {
        // Accept user ID and amount, update user's balance by deducting withdrawn amount
        $user = User::findOrFail($request->input('user_id'));
        $amount = $request->input('amount');
        // Apply withdrawal fee based on account type
        $fee = $user->account_type === 'Individual' ? $this->calculateIndividualFee($amount) : $this->calculateBusinessFee($amount);
        // Update user's balance after deducting amount and fee
        $user->balance -= ($amount + $fee);
        $user->save();
        // Record the withdrawal transaction
        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'withdrawal',
            'amount' => $amount,
            'fee' => $fee,
            'date' => now()
        ]);
        return response()->json(['message' => 'Withdrawal successful']);
    }

    // Calculate withdrawal fee for Individual accounts
    private function calculateIndividualFee($amount)
    {
        // Implement free withdrawal conditions for Individual accounts
        // Each Friday withdrawal is free of charge
        if (now()->dayOfWeek === 5) { // 5 indicates Friday
            return 0;
        }
        // The first 1K withdrawal per transaction is free
        if ($amount <= 1000) {
            return 0;
        }
        // The first 5K withdrawal each month is free
        $firstOfMonth = now()->startOfMonth();
        $lastOfMonth = now()->endOfMonth();
        $monthlyWithdrawals = Transaction::where('transaction_type', 'withdrawal')
            ->where('date', '>=', $firstOfMonth)
            ->where('date', '<=', $lastOfMonth)
            ->sum('amount');
        if ($monthlyWithdrawals + $amount <= 5000) {
            return 0;
        }
        // Otherwise, apply standard fee
        return $amount * 0.015 / 100;
    }

    // Calculate withdrawal fee for Business accounts
    private function calculateBusinessFee($amount)
    {
        // Decrease the withdrawal fee to 0.015% after a total withdrawal of 50K
        $totalWithdrawals = Transaction::where('transaction_type', 'withdrawal')->sum('amount');
        if ($totalWithdrawals >= 50000) {
            return $amount * 0.015 / 100;
        }
        // Otherwise, apply standard fee
        return $amount * 0.025 / 100;
    }
}
