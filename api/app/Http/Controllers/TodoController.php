<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Todo;
use App\Http\Resources\Todo as TodoResource;

class TodoController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $todos = Todo::all();
        return $this->responseSuccess(TodoResource::collection($todos), 'Todos retrieved with success!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'schedule' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->responseError('Validation Error.', $validator->errors());       
        }
   
        $todo = Todo::create($input);
   
        return $this->responseSuccess(new TodoResource($todo), 'Todo successfully created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $todo = Todo::find($id);
  
        if (is_null($todo)) {
            return $this->responseError('Todo not found.');
        }
   
        return $this->responseSuccess(new TodoResource($todo), 'Todo successfully retrieved.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'schedule' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->responseError('Validation Error.', $validator->errors());       
        }
   
        $todo->title = $input['title'];
        $todo->description = $input['description'];
        $todo->schedule = $input['schedule'];
        $todo->save();
   
        return $this->responseSuccess(new TodoResource($todo), 'Todo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        $todo->delete();
        return $this->responseSuccess([], 'Todo deleted with success');
    }
}
