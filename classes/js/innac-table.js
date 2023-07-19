function innac_table_click(){
    if(document.querySelector('.innac-table').hidden == false){
        document.querySelector('.innac-table').hidden = true;
        document.getElementById('innac_table_button').innerHTML = 'Show <b>Never Accessed Users</b>'
        document.getElementById('innac_table_button').className = 'btn-primary mb-2 mr-2 p-2';
    } else {
        document.querySelector('.innac-table').hidden = false;
        document.getElementById('innac_table_button').innerHTML = 'Hide <b>Never Accessed Users</b>'
        document.getElementById('innac_table_button').className = 'btn-secondary mb-2 mr-2 p-2';
    }
}