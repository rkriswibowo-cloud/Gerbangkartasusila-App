<?php

$modelsDir = __DIR__ . '/app/Models';
$files = scandir($modelsDir);

$modelsData = [
    'User.php' => [
        'search' => "protected \$fillable = [\n        'name',\n        'email',\n        'password',\n    ];",
        'replace' => "protected \$fillable = [\n        'name',\n        'email',\n        'password',\n        'avatar',\n        'phone',\n        'status',\n    ];\n\n    public function articles()\n    {\n        return \$this->hasMany(Article::class, 'author_id');\n    }\n\n    public function reviews()\n    {\n        return \$this->hasMany(Review::class);\n    }\n\n    public function favorites()\n    {\n        return \$this->hasMany(Favorite::class);\n    }"
    ],
    'DestinationCategory.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'name', 'icon', 'slug'\n    ];\n\n    public function destinations()\n    {\n        return \$this->hasMany(Destination::class, 'category_id');\n    }"
    ],
    'Destination.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'category_id', 'name', 'slug', 'description', 'address',\n        'latitude', 'longitude', 'ticket_price', 'opening_hours',\n        'thumbnail', 'is_featured', 'status'\n    ];\n\n    public function category()\n    {\n        return \$this->belongsTo(DestinationCategory::class, 'category_id');\n    }\n\n    public function images()\n    {\n        return \$this->hasMany(DestinationImage::class);\n    }\n\n    public function reviews()\n    {\n        return \$this->hasMany(Review::class);\n    }\n\n    public function favorites()\n    {\n        return \$this->hasMany(Favorite::class);\n    }"
    ],
    'DestinationImage.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'destination_id', 'image'\n    ];\n\n    public function destination()\n    {\n        return \$this->belongsTo(Destination::class);\n    }"
    ],
    'Event.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'title', 'slug', 'description', 'banner', 'location',\n        'start_date', 'end_date', 'status'\n    ];"
    ],
    'CulinaryPlace.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'name', 'slug', 'description', 'address',\n        'latitude', 'longitude', 'thumbnail'\n    ];"
    ],
    'Hotel.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'name', 'slug', 'description', 'address',\n        'latitude', 'longitude', 'thumbnail', 'phone'\n    ];"
    ],
    'Article.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'title', 'slug', 'content', 'thumbnail', 'author_id', 'published_at'\n    ];\n\n    protected \$casts = [\n        'published_at' => 'datetime',\n    ];\n\n    public function author()\n    {\n        return \$this->belongsTo(User::class, 'author_id');\n    }"
    ],
    'Gallery.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'title', 'image'\n    ];"
    ],
    'Review.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'user_id', 'destination_id', 'rating', 'comment'\n    ];\n\n    public function user()\n    {\n        return \$this->belongsTo(User::class);\n    }\n\n    public function destination()\n    {\n        return \$this->belongsTo(Destination::class);\n    }"
    ],
    'Favorite.php' => [
        'search' => "use HasFactory;",
        'replace' => "use HasFactory;\n\n    protected \$fillable = [\n        'user_id', 'destination_id'\n    ];\n\n    public function user()\n    {\n        return \$this->belongsTo(User::class);\n    }\n\n    public function destination()\n    {\n        return \$this->belongsTo(Destination::class);\n    }"
    ],
];

foreach ($modelsData as $filename => $data) {
    $filePath = $modelsDir . '/' . $filename;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        $content = str_replace($data['search'], $data['replace'], $content);
        file_put_contents($filePath, $content);
        echo "Updated $filename\n";
    } else {
        echo "File not found: $filename\n";
    }
}

echo "Models updated successfully!\n";
