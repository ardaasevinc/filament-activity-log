<?php

namespace Ardaasevinc\FilamentActivityLog\Observers;

use App\Models\ActivityLog; // Senin log modelin
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function created(Model $model)
    {
        // Model oluşturulduğunda çalışır
        $this->logActivity('created', $model);
    }

    public function updated(Model $model)
    {
        // Model güncellendiğinde çalışır
        $this->logActivity('updated', $model);
    }

    public function deleted(Model $model)
    {
        $this->logActivity('deleted', $model);
    }

    protected function logActivity(string $action, Model $model)
    {
        // Loglama işlemini buraya yaz
        // Not: Auth::user() ile işlemi yapanı alabilirsin.
    }
}