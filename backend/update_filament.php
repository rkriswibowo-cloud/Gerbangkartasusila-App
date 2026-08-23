<?php

$dir = __DIR__ . '/app/Filament/Resources';

$resources = [
    'DestinationCategoryResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('icon')->maxLength(255),
PHP,
        'table' => <<<PHP
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->searchable(),
                Tables\Columns\TextColumn::make('icon'),
PHP,
    ],
    'DestinationResource.php' => [
        'form' => <<<PHP
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->required(),
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('address')->required()->maxLength(255),
                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),
                Forms\Components\TextInput::make('ticket_price')->numeric()->default(0),
                Forms\Components\TextInput::make('opening_hours')->maxLength(255),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('destinations'),
                Forms\Components\Toggle::make('is_featured'),
                Forms\Components\Select::make('status')->options(['published' => 'Published', 'draft' => 'Draft'])->default('published')->required(),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category.name')->sortable(),
                Tables\Columns\TextColumn::make('ticket_price')->money('IDR')->sortable(),
                Tables\Columns\IconColumn::make('is_featured')->boolean(),
                Tables\Columns\TextColumn::make('status')->badge(),
PHP,
    ],
    'EventResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\RichEditor::make('description')->required()->columnSpanFull(),
                Forms\Components\FileUpload::make('banner')->image()->directory('events'),
                Forms\Components\TextInput::make('location')->required()->maxLength(255),
                Forms\Components\DatePicker::make('start_date')->required(),
                Forms\Components\DatePicker::make('end_date')->required(),
                Forms\Components\Select::make('status')->options(['published' => 'Published', 'draft' => 'Draft'])->default('published')->required(),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('banner'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('start_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('end_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
PHP,
    ],
    'CulinaryPlaceResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('address')->required()->maxLength(255),
                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('culinary'),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('address')->searchable(),
PHP,
    ],
    'HotelResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('address')->required()->maxLength(255),
                Forms\Components\TextInput::make('latitude')->numeric(),
                Forms\Components\TextInput::make('longitude')->numeric(),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('hotels'),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(255),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('phone')->searchable(),
PHP,
    ],
    'ArticleResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\RichEditor::make('content')->required()->columnSpanFull(),
                Forms\Components\FileUpload::make('thumbnail')->image()->directory('articles'),
                Forms\Components\Select::make('author_id')->relationship('author', 'name')->required(),
                Forms\Components\DateTimePicker::make('published_at'),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('author.name')->sortable(),
                Tables\Columns\TextColumn::make('published_at')->dateTime()->sortable(),
PHP,
    ],
    'GalleryResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\FileUpload::make('image')->image()->required()->directory('galleries'),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')->searchable(),
PHP,
    ],
    'UserResource.php' => [
        'form' => <<<PHP
                Forms\Components\TextInput::make('name')->required()->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->required()->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255)
                    ->dehydrated(fn (\$state) => filled(\$state))
                    ->required(fn (string \$context): bool => \$context === 'create'),
                Forms\Components\FileUpload::make('avatar')->avatar()->directory('avatars'),
                Forms\Components\TextInput::make('phone')->tel()->maxLength(255),
                Forms\Components\Select::make('status')->options(['active' => 'Active', 'inactive' => 'Inactive'])->default('active')->required(),
                Forms\Components\Select::make('roles')->multiple()->relationship('roles', 'name')->preload(),
PHP,
        'table' => <<<PHP
                Tables\Columns\ImageColumn::make('avatar')->circular(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('roles.name'),
                Tables\Columns\TextColumn::make('status')->badge(),
PHP,
    ],
];

foreach ($resources as $filename => $config) {
    $filePath = $dir . '/' . $filename;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        
        // Replace form schema
        $content = preg_replace(
            '/(public static function form\(Form \$form\): Form\s*\{\s*return \$form\s*->schema\(\[\s*)(.*?)(\s*\]\);\s*\})/s',
            '$1' . $config['form'] . '$3',
            $content
        );

        // Replace table columns
        $content = preg_replace(
            '/(public static function table\(Table \$table\): Table\s*\{\s*return \$table\s*->columns\(\[\s*)(.*?)(\s*\]\).*?;\s*\})/s',
            '$1' . $config['table'] . '$3',
            $content
        );
        
        file_put_contents($filePath, $content);
        echo "Updated \$filename\n";
    } else {
        echo "File not found: \$filename\n";
    }
}
