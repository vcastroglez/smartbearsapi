<!DOCTYPE html>
<html >
<head >
	<title >Task Image Upload</title >
	<meta charset="utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1" >
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
</head >
<body >
<div class="container mt-5" >
	<div class="card" >
		<div class="card-header" >
			<h2 >Upload Task Image</h2 >
		</div >
		<div class="card-body" >
			@if ($message = Session::get('success'))
				<div class="alert alert-success" >
					<strong >{{ $message }}</strong >
					@if (Session::has('image'))
						<p >Saved as: {{ Session::get('image') }}</p >
					@endif
				</div >
			@endif

			@if ($message = Session::get('error'))
				<div class="alert alert-danger" >
					<strong >{{ $message }}</strong >
				</div >
			@endif

			@if ($errors->any())
				<div class="alert alert-danger" >
					<ul >
						@foreach ($errors->all() as $error)
							<li >{{ $error }}</li >
						@endforeach
					</ul >
				</div >
			@endif

			<form action="{{ route('task.image.upload') }}" method="POST" enctype="multipart/form-data" >
				@csrf
				<div class="mb-3" >
					<label class="form-label" >Select Image:</label >
					<input type="file" name="image" class="form-control" >
				</div >
				<div class="mb-3" >
					<button type="submit" class="btn btn-primary" >Upload</button >
				</div >
			</form >
		</div >
	</div >
</div >
</body >
</html >
