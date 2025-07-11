<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Request;

class MessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Message $message): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(?User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(?User $user, Message $message): bool
    {
        return $this->canAccess($user, $message);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Message $message): bool
    {
        return $this->canAccess($user, $message);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Message $message): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Message $message): bool
    {
        return false;
    }

     protected function canAccess(?User $user, Message $message): bool
    {
        if ($user) {
            return true;
        }

        return $message->user_ip === Request::ip()
            && $message->created_at->gt(now()->subMinutes(5));
    }
}
