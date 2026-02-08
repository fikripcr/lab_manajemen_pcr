<?php
namespace App\Traits;

trait HashidBinding
{
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKey()
    {
        return encryptId($this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $decryptedId = decryptId($value, false);

        if ($decryptedId) {
            return $this->where($this->getRouteKeyName(), $decryptedId)->firstOrFail();
        }

        // Optional: Support raw ID for internal use/testing if needed,
        // but for high security we can just let it fail if not a valid hash.
        return $this->where($this->getRouteKeyName(), decryptId($value))->firstOrFail();
    }

    /**
     * Accessor for hashid
     */
    public function getHashidAttribute()
    {
        return encryptId($this->getAttribute($this->getRouteKeyName()));
    }
}
