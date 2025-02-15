<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="text-center text-primary">Create New Article</h2>
            
            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger"> <?php echo $error_message; ?> </div>
            <?php endif; ?>

            <form action="create.php" method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="Enter article title" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control" rows="5" placeholder="Write your content here" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Keywords</label>
                    <input type="text" name="keywords" class="form-control" placeholder="Enter keywords (optional)">
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>
                
                <div class="mb-3 text-center">
                    <img id="imagePreview" src="#" class="img-fluid rounded shadow" style="display:none; max-width: 300px; height: auto; margin-top: 10px;">
                </div>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const imagePreview = document.getElementById("imagePreview");
            imagePreview.src = URL.createObjectURL(event.target.files[0]);
            imagePreview.style.display = "block";
        }
    </script>
</body>
</html>
