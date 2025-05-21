<?php
use Illuminate\Support\Facades\DB;

if (!function_exists('is_user_mapped')) {
    function is_user_mapped($userId, $destinationId) {

        if (auth()->user()->role_id == 1) {
            return true;
        }

        // Check if user is mapped to destination (excluding superadmin)
        return DB::table('user_mapping')
            ->where('user_id', $userId)
            ->where('destination_id', $destinationId)
            ->where('role_id', '!=', 1)
            ->exists();
    }
}
