<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UAController extends Controller
{
    public function index()
    {
        return view('admin.ua.index');
    }

    public function store()
    {
        //
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->fill($request->validated());
        
        if ($user->isDirty()) {
            $user->save();
            
            return response()->json([
                'message' => 'Updated successfully',
                'changed' => $user->getDirty()
            ]);
        }
        
        return response()->json(['message' => 'No changes detected']);
        }

    public function destroy()
    {
        //
    }
}
