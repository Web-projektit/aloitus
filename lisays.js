
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block'; // Show the image
        document.getElementById('clearButton').style.display = 'block'; // Show the clear button
    };
    reader.readAsDataURL(event.target.files[0]);
}

function clearImage() {
    document.getElementById('image').value = ''; // Clear the file input
    var output = document.getElementById('preview');
    output.src = '';
    output.style.display = 'none'; // Hide the image
    document.getElementById('clearButton').style.display = 'none'; // Hide the clear button
}
