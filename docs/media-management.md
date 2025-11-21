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

## File Validation

Configure file validation rules in collections:

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('documents')
        ->acceptsMimeTypes(['application/pdf', 'application/msword'])
        ->acceptsFile(function (File $file) {
            return $file->size < 5000000; // 5MB limit
        });
}
```

## Security Considerations

### File Type Validation

Always validate file types to prevent malicious uploads:

```php
public function registerMediaCollections(): void
{
    $this->addMediaCollection('user_uploads')
        ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif']);
}
```

### File Size Limits

Set appropriate file size limits:

```php
public function registerMediaConversions(Media $media = null): void
{
    // Only apply conversions to images smaller than 10MB
    if ($media && $media->size < 10000000) {
        $this->addMediaConversion('thumb')
            ->fit(\Spatie\Image\Manipulations::FIT_CROP, 100, 100)
            ->nonQueued();
    }
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

## Helper Functions

The system includes helper functions for safe media access:

```php
// In App\Helpers\Global.php or App\Helpers\Sys.php
function getMediaUrl($model, $collectionName, $conversion = null)
{
    if ($model->hasMedia($collectionName)) {
        return $conversion 
            ? $model->getFirstMediaUrl($collectionName, $conversion)
            : $model->getFirstMediaUrl($collectionName);
    }
    
    return null;
}

function getVerifiedMediaUrl($model, $collectionName, $conversion = null)
{
    $url = getMediaUrl($model, $collectionName, $conversion);
    
    // Verify the URL is valid before returning
    if ($url && !str_starts_with($url, 'http') && !str_starts_with($url, '/storage')) {
        return asset($url);
    }
    
    return $url;
}
```

## File Organization

Uploaded files are organized in the storage directory:

```
storage/
└── app/
    └── media/
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
        ->queued(); // Process in background
}
```

### Clean Up Original Files

After conversions are complete, you may want to clean up original files:

```php
public function registerMediaConversions(Media $media = null): void
{
    $this->addMediaConversion('web')
        ->fit(\Spatie\Image\Manipulations::FIT_MAX, 1200, 1200)
        ->optimize()
        ->nonQueued();

    // Keep only converted files, remove original
    $this->deletePreservingOriginal();
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