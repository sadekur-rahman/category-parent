<?php

namespace App\Http\Controllers;

use App\Models\Head;
use Exception;
use Illuminate\Http\Request;

class PracticeController extends Controller
{
    public $response = ['status' => false, 'data' => [], 'message' => ''];
    public function index(Request $request)
    {
        $data['heads'] = Head::whereNull('parent_id')->with('children')->get();
        $data['parents'] = Head::all();
        return view('welcome', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        try {
            if ($request->id) {
                $data = Head::findOrFail($request->id);
                $data->updated_by = auth()->id();
            } else {
                $data = new Head();
                $data->created_by = auth()->id();
            }

            $data->name = $request->name;
            $data->parent_id = $request->parent_id;
            $data->save();


            $this->response['status'] = true;
            $this->response['message'] = $request->id ? 'Added Successfully' : 'Updated Successfully';
            return response()->json($this->response);
        } catch (Exception $e) {
            $this->response['status'] = false;
            $this->response['message'] = $e->getMessage();
            return response()->json($this->response);
        }
    }

    public function info(Request $request)
    {
        return (Head::findOrFail($request->id));
    }

    public function delete($id)
    {
        $data = Head::findOrFail($id);
        $data->delete();
        return back();
    }
}
