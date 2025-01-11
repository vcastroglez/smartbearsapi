<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskImageController extends Controller
{
	public function index()
	{
		return view('tasks.upload');
	}

	public function upload(Request $request)
	{
		$request->validate([
			'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
		]);

		if ($request->hasFile('image')) {
			// Get the count of existing files in the tasks directory
			$files = Storage::files('tasks');
			$fileCount = count($files);

			// New file will be number of existing files + 1
			$newFileName = ($fileCount + 1) . '.png';

			// Store the image
			$request->file('image')->storeAs('tasks', $newFileName);

			return back()
				->with('success', 'Image uploaded successfully')
				->with('image', $newFileName);
		}

		return back()->with('error', 'Please select an image to upload.');
	}
}
