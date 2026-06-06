<?php
$_ENV['DB_PORT'] = '33063';
putenv('DB_PORT=33063');

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$updated = App\Models\LandingPageContent::where('key', 'about_image')
    ->update(['value' => 'uploads/contents/about.png']);

echo "Updated rows: " . $updated . "\n";
