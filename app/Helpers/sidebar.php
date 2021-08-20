<?php

use Illuminate\Support\Facades\Http;

function has_children($rows, $id)
{
    foreach ($rows as $row) {
        if ($row['parent_id'] == $id) {
            return true;
        }
    }
    return false;
}

function build_menu($rows, $parent = 0, $menuModule)
{
    $items = [];
    foreach ($rows as $row) {
        if (!in_array($row['id_menu'], $menuModule)) {
            continue;
        }
        if ($row['parent_id'] == $parent) {
            $item = [
                'title' => $row['menu_name'],
                'page' => url($row['url'])
            ];
            if ($row['parent_id'] == 0) {
                $item['root'] = true;
                $item['icon'] = 'flaticon-' . $row['icon'];
            }
            if (has_children($rows, $row['id_menu'])) {
                $item['bullet'] = "dot";
                $item['submenu'] = build_menu($rows, $row['id_menu'], $menuModule);
            }
            array_push($items, $item);
        }
    }
    return $items;
}

function sidebar()
{
    if (!session('token') && !session('id_module')) {
        return false;
    }
    $apiMenu = config('app.api_url').'sys/menu';
    $apiMenuModule = config('app.api_url'). 'sys/menu_module/getByIdModule/' . session('id_module');
    $requestMenu = Http::withToken(session('token'))->get($apiMenu);
    $menu = ($requestMenu->successful() && $requestMenu->json()['status'] == 200) ? $requestMenu->json()['data'] : [];
    $requestMenuModule = Http::withToken(session('token'))->get($apiMenuModule);
    $dataMenuModule = ($requestMenuModule->successful() && $requestMenuModule->json()['status'] == 200) ? $requestMenuModule->json()['data'] : [];
    $menuModule = [];
    foreach ($dataMenuModule as $dmm) {
        array_push($menuModule, $dmm['id_menu']);
    }
    array_push($menu, [
        'id_menu' => 99999,
        'menu_name' => 'Logout',
        'url' => '/auth/logout',
        'parent_id' => 0,
        'icon' => 'logout'
    ]);
    array_push($menuModule, 99999);
    return build_menu($menu, 0, $menuModule);
}