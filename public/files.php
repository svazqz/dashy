<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_dir = __DIR__ . "/assets/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $filename = $_POST['filename'];
        $filepath = $target_dir . basename($filename);
        if (file_exists($filepath) && unlink($filepath)) {
            echo json_encode(['success' => true, 'message' => 'File deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error deleting file']);
        }
        exit;
    }
    
    $response = ['success' => false, 'message' => ''];
    
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $fileName = basename($file['name']);
        $targetPath = $target_dir . $fileName;
        
        $imageFileType = strtolower(pathinfo($targetPath, PATHINFO_EXTENSION));
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $response = ['success' => true, 'message' => 'File uploaded successfully'];
            } else {
                $response['message'] = 'Error uploading file';
            }
        } else {
            $response['message'] = 'Only JPG, JPEG, PNG & GIF files are allowed';
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

function getImages() {
    $target_dir = __DIR__ . "/assets/";
    $images = [];
    if (is_dir($target_dir)) {
        foreach (glob($target_dir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE) as $file) {
            $images[] = basename($file);
        }
    }
    return $images;
}
?>
<!DOCTYPE html>
<html
<head>
    <title>Image Upload</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .upload-area {
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            cursor: pointer;
        }
        .upload-area.dragover {
            border-color: #007bff;
            background: #f8f9fa;
        }
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            margin: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Upload Images</h4>
                    </div>
                    <div class="card-body">
                        <div class="upload-area" id="uploadArea">
                            <p class="mb-0">Drag and drop images here or click to select files</p>
                            <input type="file" id="fileInput" class="d-none" accept="image/*" multiple>
                        </div>
                        <div id="preview" class="d-flex flex-wrap"></div>
                        <div id="uploadProgress" class="progress mt-3 d-none">
                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="mb-0">Existing Images</h4>
                </div>
                <div class="card-body">
                    <div class="row" id="imageGallery">
                        <?php foreach(getImages() as $image): ?>
                        <div class="col-md-3 mb-3 image-item">
                            <div class="card">
                                <img src="assets/<?php echo $image; ?>" class="card-img-top" alt="<?php echo $image; ?>">
                                <div class="card-body p-2">
                                    <p class="card-text small text-truncate mb-2"><?php echo $image; ?></p>
                                    <button class="btn btn-danger btn-sm w-100 delete-image" data-filename="<?php echo $image; ?>">Delete</button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            const uploadArea = $('#uploadArea');
            const fileInput = $('#fileInput');
            const preview = $('#preview');
            const progress = $('#uploadProgress');
            const progressBar = progress.find('.progress-bar');

            uploadArea.on('click', () => fileInput.click());

            uploadArea.on('dragover dragenter', (e) => {
                e.preventDefault();
                uploadArea.addClass('dragover');
            });

            uploadArea.on('dragleave dragend drop', (e) => {
                e.preventDefault();
                uploadArea.removeClass('dragover');
            });

            uploadArea.on('drop', (e) => {
                e.preventDefault();
                const files = e.originalEvent.dataTransfer.files;
                handleFiles(files);
            });

            fileInput.on('change', (e) => {
                handleFiles(e.target.files);
            });

            $(document).on('click', '.delete-image', function() {
                const button = $(this);
                const filename = button.data('filename');
                
                if (confirm('Are you sure you want to delete this image?')) {
                    $.ajax({
                        url: 'files.php',
                        type: 'POST',
                        data: {
                            action: 'delete',
                            filename: filename
                        },
                        success: function(response) {
                            response = typeof response === 'string' ? JSON.parse(response) : response;
                            if (response.success) {
                                button.closest('.image-item').fadeOut(function() {
                                    $(this).remove();
                                });
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function() {
                            alert('Error deleting file');
                        }
                    });
                }
            });
            function handleFiles(files) {
                Array.from(files).forEach(file => {
                    if (!file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.append(`
                            <div class="position-relative">
                                <img src="${e.target.result}" class="preview-image">
                            </div>
                        `);
                    };
                    reader.readAsDataURL(file);

                    uploadFile(file);
                });
            }

            function uploadFile(file) {
                const formData = new FormData();
                formData.append('image', file);

                progress.removeClass('d-none');
                progressBar.css('width', '0%');

                $.ajax({
                    url: 'files.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        const xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                const percent = (e.loaded / e.total) * 100;
                                progressBar.css('width', percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        if (response.success) {
                            progressBar.addClass('bg-success');
                        } else {
                            progressBar.addClass('bg-danger');
                            alert(response.message);
                        }
                    },
                    error: function() {
                        progressBar.addClass('bg-danger');
                        alert('Upload failed');
                    }
                });
            }
        });
    </script>
</body>
</html>