<?php


namespace Modules\Setting;


use Illuminate\Support\Facades\Cache;
use Modules\Setting\Entities\Option;

class OptionService implements OptionServiceInterface
{
    private mixed $options;
    public function __construct()
    {
        $this->options = Cache::rememberForever('options', function() {
            return Option::all();
        });
    }

    public function cms()
    {
        return $this->options->sortBy('field')->pluck('value', 'field');
    }

    public function get($key, $default_value = null)
    {
        $item = $this->options->first(function($item, $_k) use($key, $default_value) {
            return $item->field == $key;
        }, function() use($default_value) {
            return $default_value;
        });

        $option = (is_object($item)) ? $item->value : $item;
        return ($default_value && !$option) ? $default_value : $option;
    }

    public function save(array $data, $tab)
    {
        collect($data)->each(function($value, $key) use($data, $tab) {
            Option::updateOrCreate([
                'field' => $key
                ],
                [
                    'tab' => $tab,
                    'field' => $key,
                    'value' => $value
                ]
            );
        });
        Cache::forget('options');
        Cache::forget('section4_products');
        Cache::forget('section7_products');
        Cache::forget('section9_products');
    }
}
