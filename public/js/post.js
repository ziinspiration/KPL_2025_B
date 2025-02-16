function previewImage(event) {
  const imagePreview = document.getElementById("imagePreview");
  imagePreview.src = URL.createObjectURL(event.target.files[0]);
  imagePreview.style.display = "block";
}
