<?php

namespace App\Policies;

use App\Models\Sistema;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SistemaPolicy
{
    private function tienePermiso(User $user): bool
    {
        // QUITAMOS AL CAPTURISTA DE AQUÍ Y CORREGIMOS "Titular de área"
        return in_array($user->rol, [
            'Administrador TI',
            'Titular de área',
            'Responsable'
        ]);
    }

    public function viewAny(User $user): bool { return $this->tienePermiso($user); }
    public function view(User $user, Sistema $sistema): bool { return $this->tienePermiso($user); }
    public function create(User $user): bool { return $this->tienePermiso($user); }
    public function update(User $user, Sistema $sistema): bool { return $this->tienePermiso($user); }
    public function delete(User $user, Sistema $sistema): bool { return $this->tienePermiso($user); }
    public function restore(User $user, Sistema $sistema): bool { return $this->tienePermiso($user); }
    public function forceDelete(User $user, Sistema $sistema): bool { return $this->tienePermiso($user); }
}