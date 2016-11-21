<?php namespace Octoshop\Core\Traits;

use Octoshop\Core\Util\Uuid;

trait Uuidable
{
    /**
     * Hook into the create/update events to ensure
     * orders get a UUID and it doesn't get changed.
     */
    public static function bootUuidable()
    {
        static::extend(function($model) {
            $model->bindEvent('model.beforeCreate', function() use ($model) {
                $model->attributes['uuid'] = Uuid::generate()->binary;
            });

            $model->bindEvent('model.beforeUpdate', function() use ($model) {
                $originalUuid = $model->getOriginal('uuid');

                if (is_null($originalUuid)) {
                    $model->uuid = Uuid::generate()->binary;
                } elseif ($originalUuid !== $model->attributes['uuid']) {
                    $model->uuid = $originalUuid;
                }
            });
        });
    }

    /**
     * Convert the binary UUID to a real Uuid instance
     * @return Uuid
     */
    public function getUuidAttribute($value)
    {
        return is_null($value) ? $value : Uuid::import($value);
    }

    /**
     * Find the current model by a human-readable UUID
     * @param string $uuid
     */
    public function scopeFindByUuid($query, $uuid)
    {
        $uuid = Uuid::import($uuid);

        return $query->whereUuid($uuid->binary)->firstOrFail();
    }
}
