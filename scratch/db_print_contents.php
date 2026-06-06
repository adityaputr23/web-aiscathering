<?php
$_ENV['DB_PORT'] = '33063';
putenv('DB_PORT=33063');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "LANDING PAGE CONTENTS:\n";
print_r(App\Models\LandingPageContent::all()->toArray());
