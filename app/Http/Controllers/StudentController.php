<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    //

    public function addStudent(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean'
        ]);
    
        // Handle image upload if necessary
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('students/images', 'public');
        }
    
        // Store data in the database
        // Assuming you have a Student model with appropriate fields
        $student = new Student();
        $student->name = $request->name;
        $student->description = $request->description;
        $student->image = $imagePath ?? null;
        $student->status = $request->status;
        $student->save();
    
        return response()->json(['res' => 'Student added successfully']);
    }

    public function getStudents()
    {
        $students = Student::all();
        return response()->json(['students'=>$students]);
    }

    public function getStudentData($id)
{
    $student = Student::find($id); // Fetch a single model instance by ID
    if (!$student) {
        abort(404, 'Student not found.'); // Handle the case when the student is not found
    }
    return view('edit-user', ['student' => $student]);
}

public function updateStudent(Request $request)
{
    $student = Student::find($request->id); // Fetch a single model instance by ID
    $student->name = $request->name;
    $student->description = $request->description;
    $student->status = $request->status;

    if($request->file('file'))
    {
        $file = $request->file('file');
        $fileName = time().''.$file->getClientOriginalName();
        $filePath = $file->storeAs('images',$fileName,'public');
        $student->image = $filePath;

    }
    $student->save();

    return response()->json(['result'=>'Student updated']);
}

public function deleteData($id)
{
    $student = Student::find($id);
    
    if ($student) {
        $student->delete();
        return response()->json(['result' => 'Student deleted']);
    }

    return response()->json(['result' => 'Student not found']);
}



}    