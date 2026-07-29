<?php

namespace App\Core\Audit\Traits;

use App\Core\Audit\Facades\SmartAudit;

trait AuditsModelChanges
{
    public static function bootAuditsModelChanges(): void
    {
        static::created(function ($model): void {
            SmartAudit::log(
                event: 'model.created',
                auditable: $model,
                newValues: $model->getAttributes(),
            );
        });

        static::updated(function ($model): void {
            SmartAudit::log(
                event: 'model.updated',
                auditable: $model,
                oldValues: $model->getOriginal(),
                newValues: $model->getChanges(),
            );
        });

        static::deleted(function ($model): void {
            SmartAudit::log(
                event: 'model.deleted',
                auditable: $model,
                oldValues: $model->getOriginal(),
            );
        });
    }
}
