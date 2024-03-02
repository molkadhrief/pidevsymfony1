var commenTemp ;
function likePic(postID){
    let tokenInput = document.getElementById('Vote_token');
    let tokenValue = tokenInput.value;
    let formData = new FormData();
    formData.append('vote_type', "up");
    formData.append('tokenValue', tokenValue);
    formData.append('post_id',postID);

    fetch('/vote/new',{
        method: 'POST',
        body: formData,
    }
    ).then(data => {
        location.reload();
        console.log(data);
    })
}

function dislikePic(postID){
    let tokenInput = document.getElementById('Vote_token');
    let tokenValue = tokenInput.value;
    let formData = new FormData();
    formData.append('vote_type', "down");
    formData.append('tokenValue', tokenValue);
    formData.append('post_id',postID);

    fetch('/vote/new',{
        method: 'POST',
        body: formData,
    }
    ).then(data => {
        location.reload();
        console.log(data);
    })
}
function showComment(commentId){
    commenTemp = document.getElementById("respond"+commentId);
    console.log(    commenTemp.style.display );
    if(commenTemp.style.display = "none"){
        commenTemp.style.display = "block" ;
    }else{
        commenTemp.style.display = "none" ;
    }

}
async function fetchRespond(current_post_id) {
    try {
        const fileInput = document.getElementById('Comment_file-input' + current_post_id);
        let descriptionInput = document.getElementById('Comment_description' + current_post_id);
        let tokenInput = document.getElementById('Comment_token' + current_post_id);
        let descriptionValue = descriptionInput.value;
        let tokenValue = tokenInput.value;

        if (descriptionValue === "") {
            alert("Empty field is not acceptable");
            return;
        }

        let formData = new FormData();
        formData.append('Comment_description', descriptionValue);
        formData.append('tokenValue', tokenValue);
        formData.append('post_id', current_post_id);

        let response = await fetch('/post/newcomment', {
            method: 'POST',
            body: formData,
        });

        let data = await response.json();

        post_id = data['post_id'];
        
        if (fileInput.value == "") {
            location.reload();
        } else {
            await fetchImagesRespond(post_id, fileInput);
            // Move the location.reload() inside fetchImagesRespond
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

async function fetchImagesRespond(postId, fileInput) {
    const file = fileInput.files[0];

    if (file) {
        try {
            const reader = new FileReader();

            reader.onload = async function (event) {
                const imageDataURL = event.target.result;

                try {
                    let response = await fetch(imageDataURL);
                    let blob = await response.blob();

                    let formData = new FormData();
                    formData.append('postimage', blob);
                    formData.append('post_id', postId);

                    response = await fetch('/postimage/new', {
                        method: 'POST',
                        body: formData,
                    });

                    let data = await response.json();

                    if (data['code'] === 200) {
                        console.log('Image uploaded successfully');
                    } else {
                        console.error('Error uploading image:', data['message']);
                    }
                } catch (error) {
                    console.error('Error:', error);
                } finally {
                    // After the image is uploaded or if no file
                    location.reload();
                }
            };

            reader.readAsDataURL(file);
        } catch (error) {
            console.error('Error:', error);
        }
    } else {
        console.warn('No file selected');
        location.reload(); // Reload if no file is selected
    }
}
