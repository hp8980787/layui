<?php

namespace App\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

class Button extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public string $type = '';
    public string $size = '';
    public string $color = '';
    public string $event = '';
    public string $icon = '';
    public string $text = '';
    public string $id = '';

    const EDIT_TYPE = 'edit';
    const ADD_TYPE = 'add';
    const REMOVE_TYPE = 'remove';
    const COLOR_GROUP = [
        self::ADD_TYPE => 'primary',
        self::REMOVE_TYPE => 'danger',
        self::EDIT_TYPE => 'primary',
    ];

    const ICON_GROUP = [
        self::ADD_TYPE => 'layui-icon-add-1',
        self::REMOVE_TYPE => 'layui-icon-delete',
        self::EDIT_TYPE => 'layui-icon-edit',
    ];

    const EVENT_GROUP = [
        self::ADD_TYPE => 'add',
        self::REMOVE_TYPE => 'remove',
        self::EDIT_TYPE => 'edit',
    ];

    const TEXT_GROUP = [
        self::ADD_TYPE => '新增',
        self::REMOVE_TYPE => '删除',
        self::EDIT_TYPE => '编辑',
    ];

    public function __construct($type = '', $color = '', $event = '', $size = '', $icon = '', $text = '', $id = '')
    {
        $this -> type = $type;
        $this -> size = $size ?: 'sm';
        $this -> color = $color ?: (self::COLOR_GROUP[$this -> type] ?? 'primary');
        $this -> icon = $icon ?: self::ICON_GROUP[$this -> type] ?? 'layui-icon-add';
        $this -> event = $event ?: self::EVENT_GROUP[$this -> type] ?? 'add';
        $this -> text = $text ?: self::TEXT_GROUP[$this -> type] ?? '新增';
        $this -> id = $id ;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.button');
    }
}
