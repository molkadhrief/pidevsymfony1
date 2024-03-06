async function translatetext(event,textId){
    event.preventDefault();
    var textToTranslate = document.getElementById(textId).innerText;
    let formData = new FormData();
    formData.append("text",textToTranslate);
    formData.append("target","ar");

    let jsonData = Object.fromEntries(formData.entries());  // Convert to JSON
    console.log(jsonData);
    let response = await fetch('http://127.0.0.1:8000/translateforme', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(jsonData)
    })
    if (response.ok) {
        let data = await response.json();
        document.getElementById(textId).innerText += "\n"+data.text ;
    } else {
        console.error('Request failed with status: ', response.status);
    }
}