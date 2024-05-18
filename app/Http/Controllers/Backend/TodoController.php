<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use App\Models\Admin;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class TodoController extends Controller
{
    public $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::guard('admin')->user();
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (is_null($this->user) || !$this->user->can('todo.view')) {
            abort(403, 'Sorry !! You are Unauthorized to view any todos !');
        }

        $todos = Todo::all();
        return view('backend.pages.todos.index', compact('todos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (is_null($this->user) || !$this->user->can('todo.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any todo !');
        }

        $roles  = Role::all();
        return view('backend.pages.todos.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (is_null($this->user) || !$this->user->can('todo.create')) {
            abort(403, 'Sorry !! You are Unauthorized to create any admin !');
        }

        // Validation Data
        $request->validate([
            'title' => 'required|max:50',
            'description' => 'required|max:800',
            'status' => 'required|in:open,in_progress,completed',
            
        ]);
        $userId = $this->user->id;
        
        // Create New Admin
        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->status = $request->status;
        $todo->user_id = $userId;// Store the logged-in user's Id
        $todo->save();

        
        session()->flash('success', 'Todo has been created !!');
        return redirect()->route('admin.todos.index');
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
    public function edit(int $id)
    {
        if (is_null($this->user) || !$this->user->can('todo.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any todo !');
        }

        $todo = Todo::find($id);
        
        return view('backend.pages.todos.edit', compact('todo',));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if (is_null($this->user) || !$this->user->can('todo.edit')) {
            abort(403, 'Sorry !! You are Unauthorized to edit any todo !');
        }

        // TODO: You can delete this in your local. This is for heroku publish.
        // This is only for Super Admin role,
        // so that no-one could delete or disable it by somehow.
       

        // Create New Admin
        $todo = Todo::find($id);

        // Validation Data
        $request->validate([
            'title' => 'required|max:50',
            'description' => 'required|max:800',
            'status' => 'required|in:open,in_progress,completed',
           
        ]);


        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->status = $request->status;
        
        $todo->save();

       

        session()->flash('success', 'Todo has been updated !!');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        if (is_null($this->user) || !$this->user->can('todo.delete')) {
            abort(403, 'Sorry !! You are Unauthorized to delete any admin !');
        }

       

        $todo = Todo::find($id);
        if (!is_null($todo)) {
            $admin->delete();
        }

        session()->flash('success', 'Todo has been deleted !!');
        return back();
    }
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,completed',
        ]);
    
        $todo = Todo::findOrFail($id);
        $todo->status = $request->status;
        $todo->save();
    
        session()->flash('success', 'Todo status has been created !!');
        return redirect()->route('admin.todos.index');  }
}
