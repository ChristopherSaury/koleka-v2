function openReplyModal(element){
    document.querySelector('#mythPage .com-modal').style.display = 'flex';
    document.querySelector('#comment_parentid').value = element.dataset.id;
}
function saveReply(){
    let form_element = document.getElementsByClassName('data_reply');
    let form_data = new FormData();

    for( let count = 0; count < form_element.length; count++){
        form_data.append(form_element[count].name , form_element[count].value);
    }
    document.querySelector('#mythPage .com-modal #submit-reply').disabled = true;

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', '/commentaire/ajouter/reply/' + document.querySelector('#mythPage .lg').id);
    xhttp.send(form_data);

    xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.querySelector('#mythPage .com-modal #cmt-reply #submit-reply').disabled = false;
            document.querySelector('#mythPage .com-modal #cmt-reply').reset();
            document.querySelector('#mythPage .com-modal').style.display = 'none';
        }
    }
}

function closeModal(){
    document.querySelector('#mythPage .com-modal').style.display = 'none';
}
function closeModalUpdate(){
    document.querySelector('#mythPage .update-modal').style.display = 'none';
    document.querySelector('#mythPage .update-modal #upd-form').reset();

}
async function retrieveComment(element){
    const response = await fetch('/commentaire/modifier/' + element.dataset.id);
    let data = await response.json();
    if(response){
            document.querySelector('#mythPage .update-modal #update-input').value = data.content;
            formComId = document.querySelector('#mythPage .update-modal #comment_id').value = data.id;
        document.querySelector('#mythPage .update-modal').style.display = 'flex';
    }
}

function saveUpdatedComment(){
    let form_element = document.getElementsByClassName('data_update');
    let form_data = new FormData();

    for( let count = 0; count < form_element.length; count++){
        form_data.append(form_element[count].name , form_element[count].value);
    }
    document.querySelector('#mythPage .update-modal #submit-update').disabled = true;

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', '/commentaire/modifier/handler/' + document.querySelector('#mythPage .update-modal #comment_id').value);
    xhttp.send(form_data);

    xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.querySelector('#mythPage .update-modal #submit-update').disabled = false;
            document.querySelector('#mythPage .update-modal form').reset();
            document.querySelector('#mythPage .update-modal').style.display = 'none';
        }
    }
}

function displayComment(){
    const xhttp = new XMLHttpRequest();
    xhttp.onload = function(){
        document.querySelector('#mythPage .comment-display-container').innerHTML = this.responseText;
    }
    xhttp.open('GET', '/commentaire/article/' + document.querySelector('#mythPage .lg').id);
    xhttp.send();
}

function save_data(){
    let form_element = document.getElementsByClassName('form_data');
    let form_data = new FormData();

    for( let count = 0; count < form_element.length; count++){
        form_data.append(form_element[count].name , form_element[count].value);
    }
    document.querySelector('#mythPage #cmt-form #submit').disabled = true;

    const xhttp = new XMLHttpRequest();
    xhttp.open('POST', '/commentaire/ajouter/' + document.querySelector('#mythPage .lg').id);
    xhttp.send(form_data);

    xhttp.onreadystatechange = function(){
        if(xhttp.readyState == 4 && xhttp.status == 200){
            document.querySelector('#mythPage #cmt-form #submit').disabled = false;
            document.querySelector('#mythPage #cmt-form').reset();

            document.getElementById('message').style.display = 'initial';

            setTimeout(function(){
                document.getElementById('message').style.display = 'none';
            }, 2000)
        }else if(xhttp.status == 500){
            document.querySelector('#mythPage #cmt-form #submit').disabled = false;
            document.querySelector('#mythPage #cmt-form').reset();
            document.getElementById('message-err-com').style.display = 'initial';

            setTimeout(function(){
                document.getElementById('message-err-com').style.display = 'none';
            }, 2000)
        }
    }
}

function deleteCom(element){
            const xhttp = new XMLHttpRequest();
            xhttp.open('POST', '/commentaire/supprimer/' + element.dataset.id);
            xhttp.send();
}

setInterval(function(){
    displayComment()
}, 1000)