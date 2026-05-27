<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule;

class Variable extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    # region Const
    const CACHE_KEY = 'variables';

    const TEXT = 'text';
    const SELECT = 'select';
    const CHECK_BOX = 'check_box';
    const INPUT_TYPES = [
        self::TEXT => [
            'value' => self::TEXT,
            'fa_text' => 'نوشتاری',
        ],
        self::SELECT => [
            'value' => self::SELECT,
            'fa_text' => 'انتخابی',
        ],
        self::CHECK_BOX => [
            'value' => self::CHECK_BOX,
            'fa_text' => 'چک باکس',
        ]
    ];

    const OPTIONAL = 'optional';
    const MANDATORY = 'mandatory';
    const TYPES = [
        self::OPTIONAL => [
            'value' => self::OPTIONAL,
            'fa_text' => 'اختیاری'
        ],
        self::MANDATORY => [
            'value' => self::MANDATORY,
            'fa_text' => 'اجباری'
        ]
    ];
    # endregion

    # region Scopes
    public function scopeSearch($query)
    {
        if (request()->filled('id')){
            $query->where('id', request()->get('id'));
        }
        if (request()->filled('title')){
            $query->where('title', 'LIKE', '%'.request()->get('title').'%');
        }
        if (request()->filled('type')){
            $query->where('type', request()->get('type'));
        }
        if (request()->filled('input_type')){
            $query->where('input_type', request()->get('input_type'));
        }

        return $query;
    }
    # endregion

    # region Methods
    public function inputType($index = 'fa_text'): string
    {
        return self::INPUT_TYPES[$this->input_type][$index];
    }

    public function type($index = 'fa_text'): string
    {
        return self::TYPES[$this->type][$index];
    }

    public function syncOption(string $name, ?int $id = null): VariableOption
    {
        return $this->options()->updateOrCreate(['id' => $id], ['name' => $name]);
    }

    public static function validation(): array
    {
        $variables = self::getFromCache();

        $validation['variables'] = ($variables->pluck('type')->contains(self::MANDATORY))
            ? ['required', 'array']
            : ['nullable', 'array'];

        foreach ($variables as $variable){
            $array = [];
            $array[] = ($variable->type === self::MANDATORY)
                ? 'required'
                : 'nullable';

            if ($variable->input_type === self::SELECT){
                $array[] = Rule::in($variable->options->pluck('id')->toArray());
            }

            if ($variable->input_type === self::TEXT){
                $array[] = 'string';
                $array[] = 'max:250';
            }

            $validation['variables.'.$variable->id] = $array;
        }

        return $validation;
    }

    public static function getFromCache()
    {
        return cache()->rememberForever(self::CACHE_KEY, function () {
            return self::all()->load('options');
        });
    }
    # endregion

    # region Relations
    public function options(): HasMany
    {
        return $this->hasMany(VariableOption::class);
    }
    # endregion
}
