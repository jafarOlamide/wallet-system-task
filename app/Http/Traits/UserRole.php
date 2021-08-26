<?php

namespace App\Http\Traits;
use App\Employee;

trait UserRole {
    
    protected function isAdmin($user) {
        if ($user->tokenCan('admin')) {
            return true;
        } 
        return false;
    }

    protected function isUser($user) {
        if ($user->tokenCan('user')) {
            return true;
        } 
        return false;
    }
}

?>