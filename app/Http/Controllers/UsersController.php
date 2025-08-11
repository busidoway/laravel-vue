<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Video;
use App\Models\Event;
use App\Models\UserVideo;
use App\Models\UserEvent;
use App\Models\UserSubscription;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{

    use RegistersUsers;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();

        $users = DB::table('users')
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->select('users.id', 'users.name', 'users.email', 'roles.title')
                ->paginate(10);

        return view('admin.pages.users', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all()->sortByDesc('id');

        return view('admin.pages.users_create', ['roles' => $roles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "last_name" => ['required', 'string', 'max:255'],
                "name" => ['required', 'string', 'max:255'],
                "surname" => ['required', 'string', 'max:255'],
                "phone" => ['required', 'string', 'max:255'],
                "email" => ['required', 'string', 'email', 'max:255', 'unique:users'],
                "password" => ['required', 'string', 'min:8', 'confirmed']
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $user = User::create([
            "name" => $request->name,
            "surname" => $request->surname,
            "last_name" => $request->last_name,
            "phone" => $request->phone,
            "city" => $request->city,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        $user_role = UserRole::create([
            "user_id" => $user->id,
            "role_id" => $request->role
        ]);

        // return [
        //     "status" => true,
        //     "video" => $video
        // ];

        return redirect()->route('users.edit', $user->id)->with(['status' => true]);
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
    public function edit(User $user)
    {
        $users = DB::table('users')
                ->select('users.id', 'users.name', 'users.surname', 'users.last_name', 'users.phone', 'users.city', 'users.email', 'users.password', 'roles.id as roles_id')
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->where('users.id', $user->id)
                ->first();

        $roles = Role::all()->sortByDesc('id');

        return view('admin.pages.users_edit', ['users' => $users, 'roles' => $roles]);
    }

    public function set($id)
    {

        $users = DB::table('users')
                ->select('users.id', 'users.name', 'users.email', 'users.password', 'roles.id as roles_id')
                ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                ->join('roles', 'roles.id', '=', 'user_roles.role_id')
                ->where('users.id', $id)
                ->first();

        return view('admin.pages.users_set', ['users' => $users]);
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
        if($request->change_password){
            $validator = Validator::make(
                $request->all(),
                [
                    "last_name" => ['required', 'string', 'max:255'],
                    "name" => ['required', 'string', 'max:255'],
                    "surname" => ['required', 'string', 'max:255'],
                    "phone" => ['required', 'string', 'max:255'],
                    "email" => ['required', 'string', 'email', 'max:255'],
                    "password" => ['required', 'string', 'min:8', 'confirmed']
                ]
            );
        }else{
            $validator = Validator::make(
                $request->all(),
                [
                    "last_name" => ['required', 'string', 'max:255'],
                    "name" => ['required', 'string', 'max:255'],
                    "surname" => ['required', 'string', 'max:255'],
                    "phone" => ['required', 'string', 'max:255'],
                    "email" => ['required', 'string', 'email', 'max:255'],
                ]
            );
        }

        if ($validator->fails()) {
            return redirect()->back()->with(["status" => false, "errors" => $validator->messages()]);
        }

        $user = User::find($id);

        $user->name = $request->name;
        $user->surname = $request->surname;
        $user->last_name = $request->last_name;
        $user->phone = $request->phone;
        $user->city = $request->city;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->isDirty()){
            $user->save();
        }

        $user_role = UserRole::where('user_id', $id)->first();

        $user_role->role_id = $request->role;

        if($user_role->isDirty()){
            $user_role->save();
        }

        // $user_role->toQuery()->update([
        //     'role_id' => $request->role,
        // ]);

        // $user_role_update = DB::table('user_roles')
        //                 ->where('user_id', $id)
        //                 ->update(['role_id' => $request->role]);

        return redirect()->back()->with(['status' => true]);
    }

    public function getUserEvents($user_id)
    {
        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');

        $user_event_expired = UserEvent::where('user_id', $user_id)->where('date_end', '<', $curr_date)->delete();

        $user_events = DB::table('events')
        // ->select(DB::raw("videos.*', 'user_videos.id as uv_id', 'STR_TO_DATE(user_videos.date_start, `%d.%m.%Y %H:%i:%s`)', 'STR_TO_DATE(user_videos.date_end, `%d.%m.%Y %H:%i:%s`)"))
                    ->select(DB::raw("events.*, user_events.id as uv_id, DATE_FORMAT(user_events.date_start, '%d.%m.%Y') as date_start, DATE_FORMAT(user_events.date_end, '%d.%m.%Y') as date_end"))
                    ->join('user_events', 'events.id', '=', 'user_events.event_id')
                    ->where('user_events.user_id', $user_id)
                    ->get();

        return ['user_events' => $user_events];
    }

    public function getEventList(Request $request)
    {
        $events_not_in = [];
        if(isset($request->events)) $events_not_in = json_decode($request->events);

        $events = Event::whereNotIn('id', $events_not_in)->orderBy('id', 'desc')->get();

        return ['events' => $events];
    }

    public function getCheckEventList(Request $request)
    {
        $video_data = json_decode($request->check_videos);
        $video = Video::whereIn('id', $video_data)->get();

        return ['videos' => $video];
    }

    public function getUserEventDate($id, $user_id)
    {
        // $user_video = UserVideo::where('video_id', $id)->where('user_id', $user_id)->first();

        $user_events = DB::table('user_events')
                    // ->select(DB::raw("id, DATE_FORMAT(date_start, '%d.%m.%Y') as date_start, DATE_FORMAT(date_end, '%d.%m.%Y') as date_end"))
                    ->select(DB::raw("id, date_start, date_end"))
                    ->where('event_id', $id)
                    ->where('user_id', $user_id)
                    ->first();

        return ['user_events' => $user_events];
    }

    public function storeUserEvents(Request $request, $user_id)
    {

        date_default_timezone_set("Europe/Moscow");
        $curr_date = date('Y-m-d');

        $user_events = json_decode($request->user_events);

        $user_subs = json_decode($request->user_subs);

        // return ['user_events' => $user_events, 'user_subs' => $user_subs];

        // dd($user_events);

        if(isset($request)){
            if(isset($user_events)){
                foreach($user_events as $data){
                    // if(!$data->val){
                    //     return ['status' => false, 'validate' => true];
                    // }

                    // $date_range = explode("-", $data->val);
                    // $date_start = date("Y-m-d", strtotime($data->date_start));
                    // $date_end = date("Y-m-d", strtotime($data->date_end));
                    //
                    // if($date_end < $curr_date) return ['status' => false, 'error' => 1, 'event_id' => $data->id];

                    // dd($date_start);

                    $user_event_data = UserEvent::where('user_id', $user_id)->where('event_id', $data)->first();

                    if($user_event_data){
                        // $user_event_data->date_start = $date_start;
                        // $user_event_data->date_end = $date_end;
                        if($user_event_data->isDirty()){
                            $user_event_data->save();
                        }
                    }else{
                        $user_event = UserEvent::create([
                            "user_id" => $user_id,
                            "event_id" => $data,
                            // "date_start" => $date_start,
                            // "date_end" => $date_end
                        ]);
                    }
                }
            }

            if(isset($user_subs)){
                foreach($user_subs as $key=>$data){
                    $period = $data->period;
                    $date_end_period = "+" . $period . " month";
                    $date_start = date("Y-m-d", strtotime($data->date_start));
                    $date_end = date("Y-m-d", strtotime($date_end_period, strtotime($date_start)));

                    if(isset($data->id)){
                        $user_subs_data = UserSubscription::where('user_id', $user_id)->where('event_id', $data->id)->first();

                        $user_subs_data->date_start = $date_start;
                        $user_subs_data->period = $period;
                        if($user_subs_data->isDirty()){
                            $user_subs_data->save();
                        }
                    }else{
                        $user_subs = UserSubscription::create([
                            "user_id" => $user_id,
                            "period" => $period,
                            "date_start" => $date_start,
                            "date_end" => $date_end
                        ]);
                    }

                }
            }

            return ['status' => true];
        }else{
            return ['status' => false];
        }


    }

    public function deleteUserEvent($id)
    {
        $user_event = UserEvent::find($id);
        if($user_event) {
            $user_event->delete();
            return ['delete' => 'success'];
        }
    }

    public function deleteSelectedUserEvents(Request $request, $user_id)
    {
        $events = json_decode($request->events);

        if($events){
            $user_events = UserEvent::whereIn('event_id', $events)->where('user_id', $user_id)->delete();
            return ['delete' => 'success'];
        }
    }

    public function getUserSubscriptions($user_id)
    {
        $user_subs = UserSubscription::where('user_id', $user_id)->get();

        return ['user_subscriptions' => $user_subs];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return redirect()->route('admin.users')->with(["status" => true]);
        }else{
            return redirect()->route('admin.users')->with(["status" => false]);
        }
    }
}
