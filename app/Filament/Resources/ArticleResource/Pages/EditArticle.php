<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Filament\Resources\ArticleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label(function () {
                $status = $this->data['status'] ?? 'draft';
                $publishedAt = $this->data['published_at'] ?? null;
                
                if ($status === 'published') {
                    return 'Publish Now';
                }
                
                if ($status === 'scheduled' && $publishedAt) {
                    return 'Schedule for ' . \Illuminate\Support\Carbon::parse($publishedAt)->format('M j, Y g:i A');
                }
                
                if ($status === 'scheduled') {
                    return 'Schedule';
                }
                
                if ($status === 'in_review') {
                    return 'Submit for Review';
                }

                return 'Save Draft';
            });
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
