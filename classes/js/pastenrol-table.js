function pastenrol_click(){
    if(document.querySelector('.pastenrol_table').hidden == false){
        document.querySelector('.pastenrol_table').hidden = true;
        document.getElementById('pastenrol_table_button').innerHTML = 'Show <b>Enrolment History</b>';
        document.getElementById('pastenrol_table_button').className = 'btn-primary mb-2 mr-2 p-2';
    } else {
        document.querySelector('.pastenrol_table').hidden = false;
        document.getElementById('pastenrol_table_button').innerHTML = 'Hide <b>Enrolment History</b>';
        document.getElementById('pastenrol_table_button').className = 'btn-secondary mb-2 mr-2 p-2';

    }
}
document.getElementById('pastenrol').addEventListener('submit', changetime);
function changetime(e){
    e.preventDefault();
    let params = '';
    let start = document.getElementById('pestartdate').value;
    let end = document.getElementById('peenddate').value;
    params = `start=${start}&end=${end}`;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/pastenrol.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            let output = ''
            for(i = 0; i < text.length; i++){
                output += `
                    <tr>
                        <td class='px-2'>${text[i][0]}</td>
                        <td class='px-2'>${text[i][1]}</td>
                        <td class='px-2'>${text[i][2]}</td>
                        <td class='px-2'>${text[i][3]}</td>
                    </tr>
                   `
            }
            document.getElementById('pastenrol_tbody').innerHTML = output;
        }
    }
    xhr.send(params);
}