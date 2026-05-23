<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NavLink extends Component
{
    public function __construct(
        public string $route,
        public string $icon = 'grid'
    ) {}

    public function render(): View|Closure|string
    {
        return view('components.nav-link');
    }

    public function isActive(): bool
    {
        return request()->routeIs($this->route) || request()->routeIs(rtrim($this->route, '.index') . '.*');
    }
}
