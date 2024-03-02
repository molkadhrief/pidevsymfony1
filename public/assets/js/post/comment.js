let fileInput = document.getElementById("file-input");
let imageContainer = document.getElementById("images");
let savedImages = []; // Array to store saved image data URLs
let post_id ;
let updatedImages = []; // Array to store saved image data URLs
var modal = document.getElementById("myModal");
// Get the button that opens the modal
var btn = document.getElementById("myBtn");
//images var
let imagesMoveOn=[];


let close = document.createElement("span");
close.classList.add("close");
// When the user clicks on <span> (x), close the modal

// When the user clicks anywhere outside of the modal, close it
function preview() {
    // Update the number of selected files

    // Loop through each selected file
    for (let i of fileInput.files) {
        // Create a new FileReader instance
        let reader = new FileReader();

        // Create HTML elements for displaying the image and file name
        let figure = document.createElement("figure");
        close.innerText = "X";
        figure.appendChild(close);
        // Set up the event handler for when the file is loaded
        reader.onload = () => {
            // Create an image element and set its source to the loaded file data URL
            let img = document.createElement("img");
            img.setAttribute("src", reader.result);
            figure.appendChild(img);
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
    let clickedElement = event.target;
    let clickedElementId = clickedElement.id;

    let indexToRemove = savedImages.indexOf(clickedElementId);

    if (indexToRemove !== -1) {
        savedImages.splice(indexToRemove, 1);
        let fig = document.getElementById(clickedElementId+"fig");
        fig.remove();
    }
};
async function fetchPost(e, current_post_id) {
    e.preventDefault();  // Prevent the default form submission behavior

    try {
        let descriptionInput = document.getElementById('Comment_description');
        let tokenInput = document.getElementById('Comment_token');
        let descriptionValue = descriptionInput.value;
        let tokenValue = tokenInput.value;

        if (!descriptionValue.trim()) {
            alert("Empty field is not acceptable");
        } else {
            let formData = new FormData();
            formData.append('Comment_description', descriptionValue);
            formData.append('tokenValue', tokenValue);
            formData.append('post_id', current_post_id);

            let response = await fetch('/post/newcomment', {
                method: 'POST',
                body: formData,
            });

            let data = await response.json();
            console.log(data);

            post_id = data['post_id'];

            if (savedImages.length === 0) {
                location.reload();
            } else {
                await fetchImages();
            }
        }
    } catch (error) {
        console.error('Error:', error);
    }
}



async function fetchImages() {
    console.log(savedImages);
    // Use a for...of loop to iterate over savedImages array
    for (let imageDataURL of savedImages) {
        try {
            // Convert the data URL to a Blob object
            let response = await fetch(imageDataURL);
            let blob = await response.blob();

            // Create a FormData object and append the Blob with the image data
            let formData = new FormData();
            formData.append('postimage', blob);
            formData.append('post_id', post_id);

            // Make a POST request to your Symfony controller endpoint
            response = await fetch('/postimage/new', {
                method: 'POST',
                body: formData,
            });

            let data = await response.json();

            // Handle the response from the server if needed
            if (data['code'] === 200) {
                console.log('Image uploaded successfully');
            } else {
                console.error('Error uploading image:', data['message']);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    // After all images are uploaded, reload the page
    location.reload();
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

//images go onee by one
function getImagesIdOneByOne(){
    imagesMoveOn = document.getElementsByClassName("col-lg-12");
    for (let i = 0; i < imagesMoveOn.length; i++) {
        if(i===0){
            continue;
        }
        imagesMoveOn[i].style.display = "none" ;
    }
    console.log(imagesMoveOn);
}
window.onload = function() {
    getImagesIdOneByOne();
};
function nextImage(){
    for (let i = 0; i < imagesMoveOn.length; i++) {
        console.log(imagesMoveOn[i].style.display);
        if(imagesMoveOn[i].style.display !== "none" && i+1 < imagesMoveOn.length){
            imagesMoveOn[i].style.display = "none" ;
            imagesMoveOn[i+1].style.display = "block";
            break;
        }
        
    }
}
function previousImage(){
    for (let i = 0; i < imagesMoveOn.length; i++) {
        console.log(imagesMoveOn[i].style.display);
        console.log(i);
        if(imagesMoveOn[i].style.display !== "none" && i > 0 ){
            imagesMoveOn[i].style.display = "none" ;
            imagesMoveOn[i-1].style.display = "block";
            break;
        }
        
    }
}
