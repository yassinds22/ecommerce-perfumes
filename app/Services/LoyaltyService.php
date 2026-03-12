<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class LoyaltyService
{
    /**
     * Add points to a user's balance.
     */
    public function addPoints(User $user, int $points, string $description = null, Order $order = null)
    {
        if ($points <= 0) return;

        return DB::transaction(function () use ($user, $points, $description, $order) {
            // Update or create loyalty points balance
            $balance = DB::table('loyalty_points')->updateOrInsert(
                ['user_id' => $user->id],
                ['points_balance' => DB::raw("points_balance + $points"), 'updated_at' => now()]
            );

            // Record transaction
            return DB::table('loyalty_transactions')->insert([
                'user_id' => $user->id,
                'points' => $points,
                'type' => 'earn',
                'order_id' => $order ? $order->id : null,
                'description' => $description ?: "Earned points from purchase",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });
    }

    /**
     * Use points from a user's balance.
     */
    public function redeemPoints(User $user, int $points, Order $order = null)
    {
        $currentBalance = $this->getUserPoints($user);

        if ($points <= 0 || $currentBalance < $points) {
            throw new \Exception("Insufficient points balance.");
        }

        return DB::transaction(function () use ($user, $points, $order) {
            DB::table('loyalty_points')
                ->where('user_id', $user->id)
                ->decrement('points_balance', $points);

            return DB::table('loyalty_transactions')->insert([
                'user_id' => $user->id,
                'points' => -$points,
                'type' => 'redeem',
                'order_id' => $order ? $order->id : null,
                'description' => "Redeemed points for discount",
                'created_at' => now(),
                'updated_at' => now()
            ]);
        });
    }

    /**
     * Get user's current points balance.
     */
    public function getUserPoints(User $user): int
    {
        return DB::table('loyalty_points')
            ->where('user_id', $user->id)
            ->value('points_balance') ?: 0;
    }

    /**
     * Get points history for a user.
     */
    public function getPointsHistory(User $user)
    {
        return DB::table('loyalty_transactions')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Calculate points to be earned for an order.
     * Default: 5% of total.
     */
    public function calculateEarnedPoints(float $total): int
    {
        $percent = config('loyalty.points_percent', 5);
        return (int) floor($total * ($percent / 100));
    }

    /**
     * Calculate discount value for points.
     * Default: 10 points = $1.
     */
    public function calculateDiscountValue(int $points): float
    {
        $rate = config('loyalty.redemption_rate', 10);
        return (float) ($points / $rate);
    }
}
