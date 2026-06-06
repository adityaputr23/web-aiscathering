<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "GALLERIES:\n";
print_r(App\Models\Gallery::all()->toArray());

echo "MENUS:\n";
print_r(App\Models\Menu::all()->toArray());
