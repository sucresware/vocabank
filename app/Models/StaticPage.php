<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use League\CommonMark\CommonMarkConverter;

class StaticPage extends Model
{
    protected $guarded = [];

    public function getParsedContentAttribute()
    {
        $converter = new CommonMarkConverter([
            'html_input'         => 'escape',
            'allow_unsafe_links' => false,
            'max_nesting_level'  => 10,
            'renderer'           => [
                'block_separator' => "\n",
                'inner_separator' => "\n",
                'soft_break'      => "\n",
            ],
        ]);

        return $converter->convertToHtml($this->content);
    }
}
