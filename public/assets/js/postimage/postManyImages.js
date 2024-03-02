let fileInput = document.getElementById("file-input");
let imageContainer = document.getElementById("images");
var btn = document.getElementById("myBtn");
let savedImages = []; // Array to store saved image data URLs
let post_id ;
let updatedImages = []; // Array to store saved image data URLs
var modal = document.getElementById("myModal");
// Get the button that opens the modal
var btn = document.getElementById("myBtn");
// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// delete button for images not fetched yet
let close = document.createElement("span");
close.classList.add("close");
// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
btn.onclick = function() {
  modal.style.display = "block";
  console.log(updatedImages.length);
    if(updatedImages.length != 0){
        for (let i = 0; i < updatedImages.length; i++) {
            let imageid = updatedImages[i];
            let image = document.getElementById(imageid);
            
            if (image !== null) {
                image.style.display = "block";
            } else {
                console.error("Image not found:", imageid);
            }
        }
    }
}
function preview() {
    // Update the number of selected files

    // Loop through each selected file
    for (let i of fileInput.files) {
        // Create a new FileReader instance
        let reader = new FileReader();

        // Create HTML elements for displaying the image and file name
        let figure = document.createElement("figure");
        close.innerText = "X";
        // Set up the event handler for when the file is loaded
        reader.onload = () => {
            // Create an image element and set its source to the loaded file data URL
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            figure.appendChild(img);
            figure.appendChild(close);
            // Insert the image before the file name caption
            figure.insertBefore(close,img);

            // Save the image data URL in the array
            savedImages.push(reader.result);
            close.id = reader.result ;
            figure.id = reader.result + "fig";
        }
        // Read the file as a data URL
        reader.readAsDataURL(i);

        // Append the figure element to the imageContainer
        imageContainer.appendChild(figure);
    }
}
close.onclick = function(event) {
    // Get the clicked element
    let clickedElement = event.target;
    // Get the ID of the clicked element
    let clickedElementId = clickedElement.id;

    let indexToRemove = savedImages.indexOf(clickedElementId);

    if (indexToRemove !== -1) {
        savedImages.splice(indexToRemove, 1);
        let fig = document.getElementById(clickedElementId+"fig");
        fig.remove();
    }
};
async function fetchPost() {
    let titleInput = document.getElementById('post_title');
    let descriptionInput = document.getElementById('post_description');
    let tokenInput = document.getElementById('post_token');
    let titleValue = titleInput.value;
    let descriptionValue = descriptionInput.value;
    let tokenValue = tokenInput.value;

    if (descriptionValue === "" || titleValue === "") {
        alert("Empty field is not acceptable");
        return;
    }

    let formData = new FormData();
    formData.append('post_title', titleValue);
    formData.append('post_description', descriptionValue);
    formData.append('tokenValue', tokenValue);

    try {
        const response = await fetch('/post/new', {
            method: 'POST',
            body: formData,
        });

        const data = await response.json();
        if (savedImages.length === 0) {
            location.reload();
        } else {
            post_id = data['post_id'];
            await fetchImages();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function fetchImages(){
    console.log(savedImages);
    // Loop through each saved image data URL
    for (let imageDataURL of savedImages) {
        // Convert the data URL to a Blob object
        fetch(imageDataURL)
            .then(response => response.blob())
            .then(blob => {
                // Create a FormData object and append the Blob with the image data
                let formData = new FormData();
                formData.append('postimage', blob);
                formData.append('post_id', post_id);
                // Make a POST request to your Symfony controller endpoint
                fetch('/postimage/new', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response from the server if needed
                    console.log(data);
                    if (data['code'] === 200) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            })
            .catch(error => {
                console.error('Error converting data URL to Blob:', error);
            });
    }
}
function setImage(image_id){
    updatedImages.push(image_id);
    var image = document.getElementById(image_id);
    image.style.display = "none"  ;
}
function fetchUpdateImages(post_id){
    if (updatedImages.length != 0 ){
        for (let i of updatedImages){
            deleteImage(i);
        }
    }
    console.log(savedImages);
    // Loop through each saved image data URL
    for (let imageDataURL of savedImages) {
        // Convert the data URL to a Blob object
        fetch(imageDataURL)
            .then(response => response.blob())
            .then(blob => {
                // Create a FormData object and append the Blob with the image data
                let formData = new FormData();
                formData.append('postimage', blob);
                formData.append('post_id', post_id);
                // Make a POST request to your Symfony controller endpoint
                fetch('/postimage/new', {
                    method: 'POST',
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response from the server if needed
                    console.log(data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            })
            .catch(error => {
                console.error('Error converting data URL to Blob:', error);
            });
    }
}
function deleteImage(imageId){
    let tokenInput = document.getElementById('image_token');
    let tokenValue = tokenInput.value ;
    let formData = new FormData();
     formData.append('image_id', imageId);
     formData.append('tokenValue', tokenValue);
     console.log(formData);
     console.log('/postimage/'+imageId);
     fetch('/postimage/'+imageId, {
        method: 'POST',
        body: formData
      }).then(response => response.json())
      .then(       )
      .catch(error => {
        console.error('Error:', error);
    });
}