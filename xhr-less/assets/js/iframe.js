document.addEventListener("DOMContentLoaded", () => {
    iframePost('server/iframePost.php', {}, (data) => console.log(data), () => console.log('Something went wrong during iframe POST request...'));

});
CallbackRegistry = {}; // реестр

function createIframe(name, src, debug) {
    src = src || 'javascript:false'; // пустой src

    let tmpElem = document.createElement('div');

    // в старых IE нельзя присвоить name после создания iframe, поэтому создаём через innerHTML
    tmpElem.innerHTML = '<iframe name="' + name + '" id="' + name + '" src="' + src + '">';
    let iframe = tmpElem.firstChild;

    if (!debug) {
        iframe.style.display = 'none';
    }

    document.body.appendChild(iframe);

    return iframe;
}

// функция постит объект-хэш content в виде формы с нужным url , target
// напр. postToIframe('/count.php', {a:5,b:6}, 'frame1')

function postToIframe(url, data, target) {
    let phonyForm = document.getElementById('phonyForm');
    if (!phonyForm) {
        // временную форму создаем, если нет
        phonyForm = document.createElement("form");
        phonyForm.id = 'phonyForm';
        phonyForm.style.display = "none";
        phonyForm.method = "POST";
        phonyForm.enctype = "multipart/form-data";
        document.body.appendChild(phonyForm);
    }

    phonyForm.action = url;
    phonyForm.target = target;

    // заполнить форму данными из объекта
    let html = [];
    for (let key in data) {
        let value = String(data[key]).replace(/"/g, "&quot;");
        html.push("<input type='hidden' name=\"" + key + "\" value=\"" + value + "\">");
    }
    phonyForm.innerHTML = html.join('');

    phonyForm.submit();
}

// аналогично iframeGet, но в postToIframe передаются данные data
function iframePost(url, data, onSuccess, onError) {

    let iframeOk = false;

    let iframeName = Math.random();
    let iframe = createIframe(iframeName);

    CallbackRegistry[iframeName] = function(data) {
        iframeOk = true;
        onSuccess(data);
    };

    iframe.onload = function() {
        iframe.parentNode.removeChild(iframe); // очистка
        delete CallbackRegistry[iframeName];

        if (!iframeOk) onError(); // если коллбэк не вызвался - что-то не так
    };

    postToIframe(url, data, iframeName);
}