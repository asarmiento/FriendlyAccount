<?php

namespace AccountHon\Entities;


use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Entity
{
    use SoftDeletes;
    // Don't forget to fill this array
    protected $fillable = ['name', 'url', 'icon_font'];

    public function getRules()
    {
        return ['name' => 'required',
            'url' => 'required',
            'icon_font' => 'required' ]; // TODO: Implement getRules() method.
    }

    public function getUnique($data, $datos)
    {
       // $data['name'] .= ',name,'.$datos->id;
       // $data['url'] .= ',url,'.$datos->id;// TODO: Implement getUnique() method.

        return $data;
    }

    public function Tasks()
    {
        return $this->belongsToMany(Task::getClass())->withPivot('status');
    }

    public function tasksActive($user)
    {
        return $this->belongsToMany(Task::getClass(), 'task_user')->wherePivot('status', 1)->wherePivot('user_id', $user);
    }

}
