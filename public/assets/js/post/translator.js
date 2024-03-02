async function translatetext(event,textId){
    event.preventDefault();
    var textToTranslate = document.getElementById(textId).value;
    console.log(textId);
    console.log(textToTranslate);
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
    console.log(response.json());
}