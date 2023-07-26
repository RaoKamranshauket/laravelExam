<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use DataTables;
use App\Jobs\SendEmailRegisterUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::where('id', '!=', Auth::user()->id)->select('*')->whereIn('type', [2, 3]);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {

                    $btn = '<a href="' . route('admin.users.edit', ['id' => encode($row->id)]) . '"><i class="bi bi-pencil text-primary cursor-pointer"></i></a>';
                    $btn .= '<i class="bi bi-trash3 text-primary cursor-pointer m-1 delete-record" data-id="' . encode($row->id) . '"></i>';

                    return $btn;
                })
                ->addColumn('type', function ($row) {
                    switch ($row->type) {
                        case config('constants.USER_TYPE.ADMIN'):
                            $label = "Admin";
                            break;
                        case config('constants.USER_TYPE.TEACHER'):
                            $label = "Tutor";
                            break;
                        case config('constants.USER_TYPE.STUDENT'):
                            $label = "Student";
                            break;
                        default:
                            $label = "-";
                            break;
                    }
                    return $label;
                })
                ->rawColumns(['action', 'type'])
                ->make(true);
        }
        return view('users.index');
    }
    public function create()
    {
        return view('users.create');
    }
    public function edit($id)
    {
        $data['user'] = User::findOrFail(decode($id));
        return view('users.edit', $data);
    }
    public function store(Request $request)
    {
        if ($request->hasFile('profile_pic')) {
            $fileName = time() . '.' . $request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('/uploads'), $fileName);
        } else {
            $fileName = '';
        }

        User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'profile_pic' => $fileName,
            'password' => Hash::make($request->password),
            'type' => $request->type,
            'cc_mails' => $request->type === '3' ? $request->cc_emails : null
        ]);
        $details = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'profile_pic' => $fileName,
            'password' => $request->password,
            'type' => $request->type,
            'cc_mails' => $request->type === '3' ? $request->cc_emails : null
        ];
        dispatch(new SendEmailRegisterUser($details));
        Session::flash('success_message', 'A new User has been created successfully!');
        return redirect()->route('admin.users.index');
    }
    public function update(Request $request)
    {
        $user = User::findOrFail(decode($request->id));
        $fileName = $user->profile_pic;

        if ($request->hasFile('profile_pic')) {

            $fileName = time() . '.' . $request->file('profile_pic')->getClientOriginalExtension();
            $request->file('profile_pic')->move(public_path('/uploads'), $fileName);
        }

        if ($user) {
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->cc_mails = $request->cc_emails;
            $user->profile_pic = $fileName;
            if ($request->has('password') && !empty($request->password)) {
                $user->password = Hash::make($request->password);
            }
            $user->type = $request->type;
            $user->save();
        }
        Session::flash('success_message', 'User updated successfully!');
        return redirect()->route('admin.users.index');
    }
    public function checkEmail(Request $request)
    {
        $user = User::query();
        if (isset($request->action) && $request->action == "edit") {
            $user = $user->where('email', $request->email)->where('id', '!=', decode($request->user_id))->first();
        } else {
            $user = $user->where('email', $request->email)->first();
        }

        if ($user) {
            echo 'false';
        } else {
            echo 'true';
        }
        exit;
    }
    public function destroy(Request $request)
    {
        $user = User::find(decode($request->id));
        if ($user) {
            $user->delete();
            return response()->json(['success' => true, 'message' => 'User deleted successfully!']);
        }
        return response()->json(['success' => false, 'message' => 'User not found!']);
    }
}
