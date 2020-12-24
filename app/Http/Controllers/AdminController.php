<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
use App\Category;

class AdminController extends Controller
{
    public function addTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);

        return Tag::create([
            'tagName' => $request->tagName
        ]);
    }

    public function editTag(Request $request)
    {
        $this->validate($request, [
            'tagName' => 'required'
        ]);
    
        return Tag::where('id', $request->id)->update([
            'tagName' => $request->tagName
        ]);
    }

    public function deleteTag(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);
    
        return Tag::where('id', $request->id)->delete();
    }

    public function getTag()
    {
        return Tag::latest()->get();
    }

    public function upload(Request $request) 
    {
        $this->validate($request, [
            'file' => 'required|mimes:png,jpg,jpeg' 
        ]);
        $imageName = time().'.'.$request->file->extension();
        $request->file->move(public_path('uploads'), $imageName);
        return $imageName;
    }

    public function deleteImage(Request $request)
    {
        $fileName = $request->imageName;
        $this->deleteFileFromServer($fileName);
        return 'done';
    }
    public function deleteFileFromServer($fileName)
    {
        $filePath = public_path().'/uploads/'.$fileName;
        if(file_exists($filePath)){
            @unlink($filePath);
        }
        return 'done';
    }

    public function addCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);

        return Category::create([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }

    public  function getCategory()
    {
        return Category::latest()->get();
    }

    public function editCategory(Request $request)
    {
        $this->validate($request, [
            'categoryName' => 'required',
            'iconImage' => 'required'
        ]);
    
        return Category::where('id', $request->id)->update([
            'categoryName' => $request->categoryName,
            'iconImage' => $request->iconImage
        ]);
    }
}
