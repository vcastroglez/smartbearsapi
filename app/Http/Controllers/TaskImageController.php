<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

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

	/**
	 * Get list of all uploaded task images
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function getImagesList()
	{
		try {
			$files = Storage::files('tasks');
			$imageUrls = [];

			foreach ($files as $file) {
				$filename = basename($file);
				$imageUrls[] = [
					'filename' => $filename,
					'url' => route('task.image.get', ['filename' => $filename]),
					'full_url' => URL::to(route('task.image.get', ['filename' => $filename])),
					'uploaded_at' => Storage::lastModified($file)
				];
			}

			// Sort by filename numerically
			usort($imageUrls, function ($a, $b) {
				return (int)explode('.', $a['filename'])[0] - (int)explode('.', $b['filename'])[0];
			});

			return response()->json([
				'status' => 'success',
				'count' => count($imageUrls),
				'images' => $imageUrls
			], 200);

		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to retrieve images list',
				'error' => $e->getMessage()
			], 500);
		}
	}

	/**
	 * Get specific image by filename
	 *
	 * @param string $filename
	 *
	 * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
	 */
	public function getImage($filename)
	{
		try {
			if (!Storage::exists("tasks/$filename")) {
				return response()->json([
					'status' => 'error',
					'message' => 'Image not found'
				], 404);
			}

			$file = Storage::get("tasks/$filename");
			$mimeType = Storage::mimeType("tasks/$filename");

			return response($file, 200)
				->header('Content-Type', $mimeType)
				->header('Cache-Control', 'public, max-age=86400');

		} catch (\Exception $e) {
			return response()->json([
				'status' => 'error',
				'message' => 'Failed to retrieve image',
				'error' => $e->getMessage()
			], 500);
		}
	}
}
