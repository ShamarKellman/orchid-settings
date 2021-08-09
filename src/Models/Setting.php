<?php

namespace ShamarKellman\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsMultiSource;

class Setting extends Model
{
    use Filterable, AsMultiSource;

    public const CACHE_PREFIX = 'settings-';

    public $timestamps = false;

    public $cache = true;

    protected $table = 'settings';

    protected $primaryKey = 'key';

	protected $fillable = [
		'key',
        'value',
        'options',
    ];

	protected $casts = [
        'key' =>'string',
        'value' => 'array',
        'options' => 'array',
    ];

    protected $allowedFilters = [
        'key',
        'value',
    ];

    protected $allowedSorts = [
        'key',
        'value',
    ];

    /**
     * @param string       $key
     * @param string|array $value
     */
    public function set(string $key, $value): bool
    {
        $result = $this->firstOrNew([
            'key' => $key,
        ])->fill([
            'value' => $value,
        ])->save();

        $this->cacheForget($key);

        return $result;
    }

    /**
     * @param string|array $key
     *
     * @return null
     */
    private function cacheForget($key)
    {
        foreach (Arr::wrap($key) as $value) {
            Cache::forget(self::CACHE_PREFIX . $value);
        }
    }

    /**
     * Get values.
     *
     * @param string|array $key
     * @param mixed        $default
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if (!$this->cache) {
            return $this->getNoCache($key, $default);
        }

        $cacheKey = self::CACHE_PREFIX . implode(',', (array)$key);

        return Cache::rememberForever($cacheKey, function () use ($key, $default) {
            return $this->getNoCache($key, $default);
        });
    }

    /**
     * @param string|array      $key
     * @param string|array|null $default
     *
     * @return string|array|null
     */
    public function getNoCache($key, $default = null)
    {
        if (is_array($key)) {
            $result = $this->select('key', 'value')->whereIn('key', $key)->pluck('value', 'key')->toArray();

            return empty($result) ? $default : $result;
        }

        $result = $this->select('value')->where('key', $key)->first();

        return $result === null ? $default : $result->value;
    }

    /** @param string|array $key */
    public function forget($key)
    {
        $key = Arr::wrap($key);
        $result = $this->whereIn('key', $key)->delete();
        $this->cacheForget($key);

        return $result;
    }

    /**
     * @param string|array $key
     *
     * @return null
     */
    public function cacheErase($key)
    {
        foreach (Arr::wrap($key) as $value) {
            Cache::forget(self::CACHE_PREFIX.$value);
        }
    }
}
