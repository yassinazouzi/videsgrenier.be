<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Reglage extends Model
{
    protected $table = 'reglages';

    protected $primaryKey = 'cle';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = ['cle', 'valeur'];

    private const CACHE_KEY = 'reglages.tous';

    public static function tous(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::pluck('valeur', 'cle')->all());
    }

    public static function get(string $cle, ?string $defaut = null): ?string
    {
        return static::tous()[$cle] ?? $defaut;
    }

    public static function definir(string $cle, ?string $valeur): void
    {
        static::updateOrCreate(['cle' => $cle], ['valeur' => $valeur]);
        Cache::forget(self::CACHE_KEY);
    }
}
