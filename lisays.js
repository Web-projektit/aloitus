
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.querySelector('#preview');
        output.src = reader.result;
        output.style.display = 'block'; // Show the image
        document.querySelector('#clearButton').style.display = 'block'; // Show the clear button
    };
    reader.readAsDataURL(event.target.files[0]);
}

function clearImage() {
    document.querySelector('#image').value = ''; // Clear the file input
    var output = document.querySelector('#preview');
    output.src = '';
    output.style.display = 'none'; // Hide the image
    document.querySelector('#clearButton').style.display = 'none'; // Hide the clear button
}
