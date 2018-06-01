<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class TemplateRepo extends Model
{
    protected $fillable = ['body', 'name'];

    public static function store($name, $body)
    {
    	$model = self::firstOrCreate(['name' => $name]);
    	$model->body = $body;
    	$model->save();
    }

    public static function get($name)
    {
    	$model = self::where('name', $name)->first();

    	if (!isset($model)) {
    		$body = config($name);
    		self::store($name, $body);
    	}

    	return isset($model) ? $model->body : $body;
    }

    public static function get_list()
    {
    	return self::all()
    		->mapWithKeys(function ($item) {
			    return [$item->name => $item->body];
			})
			->all();
    }

    public static function update_all($data)
    {
    	foreach ($data['tpl'] as $name => $body) {
    		self::store($name, $body);
    	}
    }
}
