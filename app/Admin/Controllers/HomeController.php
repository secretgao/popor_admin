<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Admin\Widgets\EducationStats;
use Encore\Admin\Layout\Content;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->title('教育管理系统')
            ->description('教育管理系统仪表盘')
            ->body(new EducationStats());
    }
}