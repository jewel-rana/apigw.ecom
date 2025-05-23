<?php


namespace Modules\Menu\Repository;


use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Modules\Menu\Entities\Menu;

class MenuRepository extends BaseRepository implements MenuRepositoryInterface
{
    public function __construct(Menu $model)
    {
        parent::__construct($model);
    }

    public function get($name): string
    {
        return Cache::rememberForever($name, function () use($name){
            $menu = $this->getModel()->where('name', $name)->first();
            if($menu) {
                return $this->buildMenu($menu->items, $menu->wrapper_class);
            }
            return '';
        });
    }

    private function buildMenu(object $array, $wrapper_class = 'navbar-nav nav', $child = False): string
    {
        $str = '';
        if (count($array)) {
            $str .= $child == FALSE ? '<ul class="' . $wrapper_class . '">' . PHP_EOL : '<ul class="dropdown-menu">' . PHP_EOL;
            foreach ($array as $item) {
                $active = 1===1;
                if (isset($item['childs']) && count($item['childs'])) {
                    $str .= $active ? '<li class="dropdown active parent-active">' : '<li class="dropdown">';
                    $str .= '<a class="dropdown-toggle" data-toggle="dropdown" href="'.e($item['menu_url']).'">'.e($item['name']).'';
                    $str .= '<b class="caret"></b></a>' . PHP_EOL;
                    $str .= $this->buildMenu($item->childs, '', TRUE);
                }else{
                    $str .= $active ? '<li class="active">' : '<li>';

                    $str .= ( $item['menu_class'] == 'home' ) ? '<a href="'.e($item['menu_url']).'"><i class="glyphicon glyphicon-home"></i></a>' : '<a href="'.e($item['menu_url']).'">'.e($item['name']).'</a>';
                }
                $str .= '</li>' . PHP_EOL;
            }

            $str .= '</ul>' . PHP_EOL;
        }
        return $str;
    }
}
