<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    public function wrap($style = []){

        // get the color
        if(!isset($style['color'])) {
            $style['color'] = $this->attributes['default_color'] ? $this->attributes['default_color'] : "black";
        }

        // get the size
        if(!isset($style['size'])) {
            $style['size'] = $this->attributes['default_size'] ? $this->attributes['default_size'] : "";
        }

        // Check if the id is set
        $style["id"] = isset($style["id"]) ? "id='{$style['id']}'" : null;
        $style["title"] = isset($style["title"]) ? $style["title"] : $this->attributes['name'];

        return "<i
                {$style["id"]}
                class='fa fa-{$this->attributes['id']} {$style['size']}'
                style='color:{$style['color']}'
                title='{$style["title"]}'
                aria-hidden='true'></i>";
    }
}
