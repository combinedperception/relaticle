<?php

declare(strict_types=1);

namespace App\Support\RichEditor;

use Filament\Forms\Components\RichEditor\FileAttachmentProviders\Contracts\FileAttachmentProvider;
use Filament\Forms\Components\RichEditor\RichContentAttribute;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

final readonly class R2FileAttachmentProvider implements FileAttachmentProvider
{
    private const DISK = 'r2';

    private const DIRECTORY = 'rich-editor-attachments';

    public function attribute(RichContentAttribute $attribute): static
    {
        return $this;
    }

    public function getFileAttachmentUrl(mixed $file): ?string
    {
        if (blank($file)) {
            return null;
        }

        return Storage::disk(self::DISK)->url((string) $file);
    }

    public function saveUploadedFileAttachment(TemporaryUploadedFile $file): mixed
    {
        $extension = $file->getClientOriginalExtension();
        $path = self::DIRECTORY.'/'.Str::uuid().'.'.$extension;

        Storage::disk(self::DISK)->put($path, $file->readStream(), 'public');

        return $path;
    }

    public function getDefaultFileAttachmentVisibility(): string
    {
        return 'public';
    }

    public function isExistingRecordRequiredToSaveNewFileAttachments(): bool
    {
        return false;
    }

    /**
     * @param  array<mixed>  $exceptIds
     */
    public function cleanUpFileAttachments(array $exceptIds): void
    {
        // Orphan cleanup is not implemented — files accumulate until manual pruning.
        // Implement with a scheduled command if storage costs become a concern.
    }
}
