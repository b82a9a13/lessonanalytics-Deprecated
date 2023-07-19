function newusers_click(){
    if(document.querySelector('.newusers_table').hidden == false){
        document.querySelector('.newusers_table').hidden = true;
        document.getElementById('newusers_table_button').innerHTML = 'Show <b>New User History';
        document.getElementById('newusers_table_button').className = 'btn-primary mb-2 mr-2 p-2';
    } else {
        document.querySelector('.newusers_table').hidden = false;
        document.getElementById('newusers_table_button').innerHTML = 'Hide <b>New User History';
        document.getElementById('newusers_table_button').className = 'btn-secondary mb-2 mr-2 p-2';
    }
}
document.getElementById('newusers').addEventListener('submit', changetimenewuser);
function changetimenewuser(e){
    e.preventDefault();
    let params = '';
    let start = document.getElementById('nustartdate').value;
    let end = document.getElementById('nuenddate').value;
    params = `start=${start}&end=${end}`;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/newusers.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            let output = '';
            for(i = 0; i < text.length; i++){
                output += `
                    <tr>
                        <td class='px-2'>${text[i][0]}</td>
                        <td class='px-2'>${text[i][1]}</td>
                        <td class='px-2'>${text[i][2]}</td>
                    </tr>
                    `
            }
            document.getElementById('newusers_tbody').innerHTML = output;
        }
    }
    xhr.send(params);
}