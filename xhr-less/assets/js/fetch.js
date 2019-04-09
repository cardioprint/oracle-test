
//all modern browser support this (https://caniuse.com/#search=fetch)
fetch('fetch.json')
    .then(resp => {
        if(resp.ok){
            return resp.json();
        }
        throw resp.statusText;
    })
    .then(data => console.log(data))
    .catch(error => console.error('Error during fetching: ' + error));