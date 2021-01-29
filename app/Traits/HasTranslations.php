<?php

namespace App\Traits;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;

/**
 * Overrides the default HasTranslations to automatically display the right translation
 */
trait HasTranslations
{
    use BaseHasTranslations;

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = parent::toArray();
        foreach ($this->getTranslatableAttributes() as $field) {
            $attributes[$field] = $this->getTranslation($field, \App::getLocale());
        }
        return $attributes;
    }
}
