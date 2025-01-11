<!DOCTYPE html>
<html >
<head >
	<title >Task Image Upload</title >
	<meta charset="utf-8" >
	<meta name="viewport" content="width=device-width, initial-scale=1" >
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" >
	<style >
		.drop-zone {
			width: 100%;
			min-height: 200px;
			border: 2px dashed #0d6efd;
			border-radius: 8px;
			padding: 20px;
			text-align: center;
			background: #f8f9fa;
			transition: all 0.3s ease;
			margin-bottom: 20px;
		}

		.drop-zone.dragover {
			background: #e2e8f0;
			border-color: #0b5ed7;
		}

		.drop-zone p {
			margin: 0;
			font-size: 1.2em;
			color: #6c757d;
		}

		#preview {
			display: flex;
			flex-wrap: wrap;
			gap: 10px;
			margin-top: 20px;
		}

		.preview-image {
			width: 100px;
			height: 100px;
			object-fit: cover;
			border-radius: 4px;
		}

		#uploadProgress {
			display: none;
			margin-top: 20px;
		}
	</style >
</head >
<body >
<div class="container mt-5" >
	<div class="card" >
		<div class="card-header" >
			<h2 >Upload Task Images</h2 >
		</div >
		<div class="card-body" >
			<div id="alerts" ></div >

			<form id="uploadForm" enctype="multipart/form-data" >
				@csrf
				<div class="drop-zone" id="dropZone" >
					<p >Drag and drop images here or click to select files</p >
					<input type="file" name="images[]" id="fileInput" multiple accept="image/*" style="display: none;" >
				</div >

				<div id="preview" ></div >

				<div id="uploadProgress" class="progress" >
					<div class="progress-bar" role="progressbar" style="width: 0%" ></div >
				</div >

				<button type="submit" class="btn btn-primary mt-3" >Upload Images</button >
			</form >
		</div >
	</div >
</div >

<script >
	document.addEventListener('DOMContentLoaded', function () {
		const dropZone = document.getElementById('dropZone');
		const fileInput = document.getElementById('fileInput');
		const preview = document.getElementById('preview');
		const uploadForm = document.getElementById('uploadForm');
		const progressBar = document.querySelector('.progress-bar');
		const progressDiv = document.getElementById('uploadProgress');
		const alerts = document.getElementById('alerts');

		// Handle drag and drop events
		dropZone.addEventListener('dragover', (e) => {
			e.preventDefault();
			dropZone.classList.add('dragover');
		});

		dropZone.addEventListener('dragleave', () => {
			dropZone.classList.remove('dragover');
		});

		dropZone.addEventListener('drop', (e) => {
			e.preventDefault();
			dropZone.classList.remove('dragover');
			handleFiles(e.dataTransfer.files);
		});

		// Handle click to select files
		dropZone.addEventListener('click', () => {
			fileInput.click();
		});

		fileInput.addEventListener('change', () => {
			handleFiles(fileInput.files);
		});

		function handleFiles(files) {
			preview.innerHTML = '';
			for (const file of files) {
				if (file.type.startsWith('image/')) {
					const reader = new FileReader();
					reader.onload = (e) => {
						const img = document.createElement('img');
						img.src = e.target.result;
						img.classList.add('preview-image');
						preview.appendChild(img);
					};
					reader.readAsDataURL(file);
				}
			}
		}

		// Handle form submission
		uploadForm.addEventListener('submit', async (e) => {
			e.preventDefault();
			const formData = new FormData(uploadForm);

			try {
				progressDiv.style.display = 'block';
				progressBar.style.width = '0%';

				const response = await fetch('{{ route('task.image.upload') }}', {
					method: 'POST',
					body: formData,
					headers: {
						'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
					},
					onUploadProgress: (progressEvent) => {
						const percentCompleted = Math.round(( progressEvent.loaded * 100 ) / progressEvent.total);
						progressBar.style.width = percentCompleted + '%';
					}
				});

				const result = await response.json();

				alerts.innerHTML = `
                        <div class="alert alert-${result.status === 'success' ? 'success' : 'danger'}">
                            ${result.message}
                            ${result.errors && result.errors.length > 0 ?
					'<ul>' + result.errors.map(error => `<li>${error}</li>`).join('') + '</ul>'
					: ''}
                        </div>
                    `;

				if (result.status === 'success') {
					uploadForm.reset();
					preview.innerHTML = '';
				}

			} catch (error) {
				alerts.innerHTML = `
                        <div class="alert alert-danger">
                            Upload failed: ${error.message}
                        </div>
                    `;
			}

			progressDiv.style.display = 'none';
		});
	});
</script >
</body >
</html >
