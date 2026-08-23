<?php

$migrationsDir = __DIR__ . '/database/migrations';
$files = scandir($migrationsDir);

$schemas = [
    'create_users_table.php' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamp('email_verified_at')->nullable();
            \$table->string('password');
            \$table->string('avatar')->nullable();
            \$table->string('phone')->nullable();
            \$table->string('status')->default('active');
            \$table->rememberToken();
            \$table->timestamps();
PHP,

    'create_destination_categories_table.php' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('icon')->nullable();
            \$table->string('slug')->unique();
            \$table->timestamps();
PHP,

    'create_destinations_table.php' => <<<PHP
            \$table->id();
            \$table->foreignId('category_id')->constrained('destination_categories')->cascadeOnDelete();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description');
            \$table->string('address');
            \$table->decimal('latitude', 10, 8)->nullable();
            \$table->decimal('longitude', 11, 8)->nullable();
            \$table->decimal('ticket_price', 10, 2)->default(0);
            \$table->string('opening_hours')->nullable();
            \$table->string('thumbnail')->nullable();
            \$table->boolean('is_featured')->default(false);
            \$table->string('status')->default('published');
            \$table->timestamps();
PHP,

    'create_destination_images_table.php' => <<<PHP
            \$table->id();
            \$table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            \$table->string('image');
            \$table->timestamps();
PHP,

    'create_events_table.php' => <<<PHP
            \$table->id();
            \$table->string('title');
            \$table->string('slug')->unique();
            \$table->text('description');
            \$table->string('banner')->nullable();
            \$table->string('location');
            \$table->date('start_date');
            \$table->date('end_date');
            \$table->string('status')->default('published');
            \$table->timestamps();
PHP,

    'create_culinary_places_table.php' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description');
            \$table->string('address');
            \$table->decimal('latitude', 10, 8)->nullable();
            \$table->decimal('longitude', 11, 8)->nullable();
            \$table->string('thumbnail')->nullable();
            \$table->timestamps();
PHP,

    'create_hotels_table.php' => <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('slug')->unique();
            \$table->text('description');
            \$table->string('address');
            \$table->decimal('latitude', 10, 8)->nullable();
            \$table->decimal('longitude', 11, 8)->nullable();
            \$table->string('thumbnail')->nullable();
            \$table->string('phone')->nullable();
            \$table->timestamps();
PHP,

    'create_articles_table.php' => <<<PHP
            \$table->id();
            \$table->string('title');
            \$table->string('slug')->unique();
            \$table->longText('content');
            \$table->string('thumbnail')->nullable();
            \$table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            \$table->timestamp('published_at')->nullable();
            \$table->timestamps();
PHP,

    'create_galleries_table.php' => <<<PHP
            \$table->id();
            \$table->string('title');
            \$table->string('image');
            \$table->timestamps();
PHP,

    'create_reviews_table.php' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            \$table->integer('rating');
            \$table->text('comment');
            \$table->timestamps();
PHP,

    'create_favorites_table.php' => <<<PHP
            \$table->id();
            \$table->foreignId('user_id')->constrained()->cascadeOnDelete();
            \$table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            \$table->timestamps();
PHP,
];

foreach ($files as $file) {
    if ($file === '.' || $file === '..') continue;
    
    foreach ($schemas as $key => $replacement) {
        if (str_ends_with($file, $key)) {
            $filePath = $migrationsDir . '/' . $file;
            $content = file_get_contents($filePath);
            
            if ($key === 'create_users_table.php') {
                $target = <<<PHP
            \$table->id();
            \$table->string('name');
            \$table->string('email')->unique();
            \$table->timestamp('email_verified_at')->nullable();
            \$table->string('password');
            \$table->rememberToken();
            \$table->timestamps();
PHP;
                $content = str_replace($target, $replacement, $content);
            } else {
                $target = <<<PHP
            \$table->id();
            \$table->timestamps();
PHP;
                $content = str_replace($target, $replacement, $content);
            }
            
            file_put_contents($filePath, $content);
            echo "Updated $file\n";
        }
    }
}

echo "Migrations updated successfully!\n";
