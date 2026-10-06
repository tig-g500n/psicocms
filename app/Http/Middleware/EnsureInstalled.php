<?php

namespace App\Http\Middleware;

use App\Models\Psicologa;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EnsureInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->instalado() && ! $request->is('instalacion', 'instalacion/*')) {
            return redirect('/instalacion');
        }

        if ($this->instalado() && $request->is('instalacion', 'instalacion/*')) {
            return redirect('/');
        }

        return $next($request);
    }

    private function instalado(): bool
    {
        try {
            return Schema::hasTable('psicologas') && Psicologa::query()->exists();
        } catch (Throwable) {
            return false;
        }
    }
}
