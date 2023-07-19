function c_table_click(){
    if(document.querySelector('.c-table').hidden == false){
        document.querySelector('.c-table').hidden = true;
        document.getElementById('c_table_button').innerHTML = "Show <b>All Learners</b>";
        document.getElementById('c_table_button').className = 'btn-primary mb-2 mr-2 p-2';
    } else {
        document.querySelector('.c-table').hidden = false;
        document.getElementById('c_table_button').innerHTML = "Hide <b>All Learners</b>";
        document.getElementById('c_table_button').className = 'btn-secondary mb-2 mr-2 p-2';
    }
}