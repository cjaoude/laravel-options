<?php

namespace Appstract\Options;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Casts.
     *
     * @var array
     */
    protected $casts = [
        'value' => 'json',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var [type]
     */
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * Determine if the given option value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function exists($key)
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Get the specified option value.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ($option = self::where('key', $key)->first()) {
            return $option->value;
        }

        return $default;
    }

    /**
     * Set and return the given option value/s.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        $keys = is_array($key) ? $key : [$key => $value];

        foreach ($keys as $key => $value) {
            self::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return self::whereIn('key', $keys)->get();
    }

    /**
     * Remove/delete the specified option value.
     *
     * @param  string  $key
     * @return bool
     */
    public function remove($key)
    {
        return (bool) self::where('key', $key)->delete();
    }

    /**
     * Get or insert the specified option value.
     *
     * Note: the intent here is to always have
     * a populated list of available options.
     *
     * @param  string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function getsert($key, $default = false)
    {
        if ($option = self::where('key', $key)->first()) {
            return $option->value;
        }

        return self::set($key, $default);
    }

    /**
     * Get the list of option values.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return self::all();
    }
}
