<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Info;
use Illuminate\Support\Carbon;


class InfoController extends Controller
{
    
    public function Index(Request $request){
        // dd($request);
        if($request->input('begin') !== null) {
            // $result = Info::whereRaw('UNIX_TIMESTAMP(date)  > ? AND UNIX_TIMESTAMP(date)  < ?  ', [Carbon::createFromFormat('Y-m-d', $request->input('begin'))->timestamp, Carbon::createFromFormat('Y-m-d', $request->input('end'))->timestamp])->get();
            // dd($result);
            // dd(Carbon::createFromFormat('Y-m-d', $request->input('begin'))->timestamp);
            // dd($request);
            // dd(Info::rat($request->input('begin'), $request->input('end'))->get());
            $info = Info::rat($request->input('begin'), $request->input('end'))->get();
            
            return Inertia::render('Dashboard',['info' => $info]);
        }
          
        
        $info = Info::all();
        return Inertia::render('Dashboard',['info' => $info, 'name'=> 'ionut']);
    }

    public function show(Request $request){
        $info = Info::find($request->id);
        // dd($id);
        return Inertia::render('Dashboard',['title'=>$info->title, 'timestamp'=>$info->timestamp]);
    }

    public function store(Request $request){
        $info = new Info();
        $info->title = $request->title;
        $date;
        if(isset($request->date)){
            $date = $request->date;
            $arr = explode("/",$date);
            $new_date = $arr[2] . "-" . $arr[0] . "-" .$arr[1];
            $date = $new_date;
        }
       
        
        $info->date = isset($request->date) ? $date : date_format(now(), 'Y-m-d');
        $info->time = isset($request->time) ? $request->time : date_format(now(), 'H:i:s');
        $info->save();
        return redirect('/dashboard');
    }

    public function update(Request $request){
        $info = Info::find($request->id);
        $info->title = $request->title;
        // $info->timestamp = isset($request->timestamp) ? $request->timestamp : date_format(now(), 'd-m-Y H:i:s');
        $info->save();
        return redirect('/dashboard');
    }

    public function delete(Request $request){
        $info = Info::find($request->id);
        $info->delete();
        return redirect('/dashboard');
    }

}
