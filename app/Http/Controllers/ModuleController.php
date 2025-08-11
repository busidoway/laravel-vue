<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\ModuleSection;
use App\Models\TextBlock;

use Illuminate\Support\Facades\Route;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($page)
    {

        $arr = array();

        $page_route = Route::currentRouteName();
        $page_curr = Page::where('name', $page)->value('id');

        $module_section = ModuleSection::where('page_id', $page_curr)->get();
        // $module_section_id = ModuleSection::where('page_id', $page_curr)->value('id');
        // $page_id = Page::all();

        foreach($module_section as $ms){
            if($ms['module'] == 'text_block'){
                // $arr[]['module'] = 'text_block';
                $arr[] = TextBlock::where('module_section_id', $ms['id'])->get();
            }elseif($ms['module'] == 'news'){
                // $arr[]['module'] = 'news';
                $arr[] = News::where('module_section_id', $ms['id'])->get();
            }
        }

        return $arr;
        $route = 'pages.'.$page;
        // return view($route, ['arr' => $arr]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
