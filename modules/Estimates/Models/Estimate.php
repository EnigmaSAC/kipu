<?php

namespace Modules\Estimates\Models;

use App\Models\Document\Document;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Estimates\Database\Factories\Estimate as EstimateFactory;

class Estimate extends Document
{
    public const TYPE = 'estimate';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->addCloneableRelation('extra_param');
    }

    public function invoices(): BelongsToMany
    {
        return $this->belongsToMany(EstimateDocument::class);
    }

    public function extra_param(): HasOne
    {
        return $this->hasOne(EstimateExtraParameter::class, 'document_id')
                    ->withDefault(['expire_at' => null]);
    }

    public function scopeEstimate(Builder $query): Builder
    {
        return $query->where("{$this->table}.type", '=', self::TYPE);
    }

    public function scopeExpire($query, $date)
    {
        return $query->join('estimates_extra_parameters', 'document_id', '=', "{$this->table}.id")->whereDate('expire_at', '=', $date);
    }

    public function scopeInvoiced($query)
    {
        return $query->where('status', 'invoiced');
    }

    public function scopeNotInvoiced($query)
    {
        return $query->where('status', '<>', 'invoiced');
    }

    public function scopeFuture(Builder $query): Builder
    {
        return $query->whereIn($this->qualifyColumn('status'), $this->getEstimatesStatusesForFuture());
    }

    private function getEstimatesStatusesForFuture(): array
    {
        return [
            'approved',
            'draft',
            'expired',
            'sent',
            'invoiced',
        ];
    }
    // @todo test duplicate for extra params
    public function onCloning($src, $child = null)
    {
        parent::onCloning($src, $child);
    }

    public function getInvoicedAtAttribute($value): string
    {
        return $this->invoices()->orderBy('created_at', 'desc')->first();
    }

    public function getStatusLabelAttribute(): string
    {
        $label = parent::getStatusLabelAttribute();

        return match ($this->status) {
            'approved' => 'status-success',
            'invoiced' => 'status-info',
            'refused' => 'status-danger',
            'expired' => 'status-secondary',
            default => $label,
        };
    }

    /**
     * Get the line actions.
     *
     * @return array
     */
    public function getLineActionsAttribute()
    {
        $actions = [];

        try {
            $actions[] = [
                'title' => trans('general.show'),
                'icon' => 'visibility',
                'url' => route('estimates.estimates.show', $this->id),
                'permission' => 'read-estimates-estimates',
                'attributes' => [
                    'id' => 'index-more-actions-show-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            if (! $this->reconciled) {
                $actions[] = [
                    'title' => trans('general.edit'),
                    'icon' => 'edit',
                    'url' => route('estimates.estimates.edit', $this->id),
                    'permission' => 'update-estimates-estimates',
                    'attributes' => [
                        'id' => 'index-more-actions-edit-' . $this->id,
                    ],
                ];
            }
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.duplicate'),
                'icon' => 'file_copy',
                'url' => route('estimates.estimates.duplicate', $this->id),
                'permission' => 'create-estimates-estimates',
                'attributes' => [
                    'id' => 'index-more-actions-duplicate-' . $this->id,
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.print'),
                'icon' => 'print',
                'url' => route('estimates.estimates.print', $this->id),
                'permission' => 'read-estimates-estimates',
                'attributes' => [
                    'id' => 'index-more-actions-print-' . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'title' => trans('general.download_pdf'),
                'icon' => 'pdf',
                'url' => route('estimates.estimates.pdf', $this->id),
                'permission' => 'read-estimates-estimates',
                'attributes' => [
                    'id' => 'index-more-actions-pdf-' . $this->id,
                    'target' => '_blank',
                ],
            ];
        } catch (\Exception $e) {}

        $actions[] = [
            'type' => 'divider',
        ];

        try {
            $actions[] = [
                'type' => 'button',
                'title' => trans('general.share_link'),
                'icon' => 'share',
                'url' => route('estimates.modals.estimates.share.create', $this->id),
                'permission' => 'read-estimates-estimates',
                'attributes' => [
                    'id'        => 'index-more-actions-share-link-' . $this->id,
                    '@click'    => 'onShareLink("' . route('modals.'. 'estimates.estimates.share.create', $this->id) . '")',
                ],
            ];
        } catch (\Exception $e) {}

        try {
            $actions[] = [
                'type' => 'button',
                'title' => trans('invoices.send_mail'),
                'icon' => 'email',
                'url' => route('estimates.modals.estimates.emails.create', $this->id),
                'permission' => 'read-estimates-estimates',
                'attributes' => [
                    'id'        => 'index-more-actions-send-email-' . $this->id,
                    '@click'    => 'onSendEmail("' . route('estimates.modals.estimates.emails.create', $this->id) . '")',
                ],
            ];
        } catch (\Exception $e) {}

        $actions[] = [
            'type' => 'divider',
        ];

        try {
            $actions[] = [
                'type' => 'delete',
                'icon' => 'delete',
                'title' => trans('general.delete'),
                'route' => 'estimates.estimates.destroy',
                'permission' => 'delete-estimates-estimates',
                'model' => $this,
            ];
        } catch (\Exception $e) {}


        return $actions;
    }

    /**
     * @inheritDoc
     */
    protected static function newFactory(): Factory
    {
        return EstimateFactory::new();
    }
}
