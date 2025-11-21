# Media Management

## Overview

The system implements comprehensive media management using the Spatie Laravel Media Library package. This allows for efficient handling of file uploads, image processing, and media organization.

## Spatie Laravel Media Library Implementation

The system uses Spatie's Laravel Media Library for handling file uploads and media management with the following features:

- File uploads with multiple collection support
- Image conversions and manipulations
- Responsive image generation
- File validation and security checks
- Integration with cloud storage (if configured)

## Basic Implementation

### Model Configuration

To implement media management in a model, use the `HasMedia` interface and `InteractsWithMedia` trait:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('product_images')
            ->singleFile()
            ->useFallbackUrl('/assets-admin/img/default-product-image.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-product-image.jpg'));

        $this->addMediaCollection('product_attachments')
            ->useFallbackUrl('/assets-admin/img/default-attachment.jpg')
            ->useFallbackPath(public_path('/assets-admin/img/default-attachment.jpg'));
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 150, 150)
            ->nonQueued();

        $this->addMediaConversion('preview')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 400, 400)
            ->nonQueued();
    }
}
```

### Controller Implementation

In your controller, handle file uploads as follows:

```php
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'product_images' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB
        'product_attachments' => 'nullable|mimes:pdf,doc,docx,zip|max:20480', // 20MB
    ]);

    $product = Product::create($request->except(['product_images', 'product_attachments']));

    // Handle product image upload
    if ($request->hasFile('product_images')) {
        $product->addMedia($request->file('product_images'))
            ->withCustomProperties(['uploaded_by' => auth()->id()])
            ->toMediaCollection('product_images');
    }

    // Handle product attachments
    if ($request->hasFile('product_attachments')) {
        foreach ($request->file('product_attachments') as $attachment) {
            if ($attachment->isValid()) {
                $product->addMedia($attachment)
                    ->withCustomProperties(['uploaded_by' => auth()->id()])
                    ->toMediaCollection('product_attachments');
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
}
```

### View Implementation

In your views, use the media library methods to access and display media:

```blade
{{-- Get featured image with conversion --}}
@if($product->hasMedia('product_images'))
    <img src="{{ $product->getFirstMediaUrl('product_images', 'preview') }}" 
         alt="Product Image" 
         class="img-fluid">
@else
    <img src="{{ $product->getFirstMediaUrl('product_images') }}" 
         alt="Product Image" 
         class="img-fluid">
@endif

{{-- List all attachments --}}
@if($product->hasMedia('product_attachments'))
    <div class="attachments-list">
        @foreach($product->getMedia('product_attachments') as $media)
            <div class="attachment-item">
                <a href="{{ $media->getUrl() }}" target="_blank">
                    <i class="bx bx-paperclip"></i> {{ $media->file_name }}
                </a>
                <small class="text-muted">({{ formatFileSize($media->size) }})</small>
            </div>
        @endforeach
    </div>
@endif
```

## Collection Management

### Single File Collections

For single file uploads (like profile photos or featured images):

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('avatar')
        ->singleFile()
        ->useFallbackUrl('/assets-admin/img/default-avatar.jpg')
        ->useFallbackPath(public_path('/assets-admin/img/default-avatar.jpg'));
}
```

### Multiple File Collections

For multiple file uploads:

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('gallery_images');
    $this->addMediaCollection('documents');
}
```

## Image Conversions

Define image conversions for different display purposes:

```php
public function registerMediaConversions(Media $media = null): void
{
    // Small thumbnail
    $this->addMediaConversion('thumb')
        ->fit(\Spatie\Image\Manipulations::FIT_CROP, 100, 100)
        ->nonQueued();

    // Medium preview
    $this->addMediaConversion('preview')
        ->fit(\Spatie\Image\Manipulations::FIT_CROP, 400, 400)
        ->nonQueued();

    // Large display
    $this->addMediaConversion('large')
        ->fit(\Spatie\Image\Manipulations::FIT_MAX, 1200, 1200)
        ->nonQueued();
}
```

## Advanced Features

### Custom Properties

Add custom properties to uploaded media:

```php
$model->addMedia($file)
    ->withCustomProperties(['owner_id' => auth()->id(), 'is_public' => false])
    ->toMediaCollection('collection_name');
```

### Responsive Images

Generate responsive image sets:

```php
public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('responsive')
        ->width(360)
        ->height(240)
        ->optimize()
        ->performOnCollections('images');
}
```

### Image Optimization

Enable image optimization for better performance:

```php
public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('optimized')
        ->optimize()
        ->performOnCollections('images');
}
```

## File Organization

Uploaded files are organized in the storage directory:

```
storage/
└── app/
    └── public/
        └── uploads/
            ├── model_type/
            │   └── collection_name/
            │       ├── original/
            │       └── conversions/
            └── ...
```

## Performance Optimization

### Queue Media Processing

For large files or many conversions, process media in the background:

```php
public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('large')
        ->fit(\Spatie\Image\Manipulations::FIT_MAX, 1200, 1200)
        ->queued(); // Process in background or ->nonQueued()
}
```

## Troubleshooting

### Common Issues

1. **File uploads failing**: Check storage disk configuration in `config/filesystems.php`
2. **Conversions not generating**: Ensure image processing libraries (like GD or Imagick) are installed
3. **File permissions**: Ensure the storage directory has proper write permissions

### Debugging

Enable media library logging for debugging purposes:

```php
// In config/media-library.php
'log_all_events' => env('MEDIA_LIBRARY_LOG_ALL_EVENTS', false),
```

The media management system provides a robust foundation for handling all types of file uploads in the application while maintaining security and performance.
