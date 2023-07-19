function group_click(){
    if(document.querySelector('.group_table').hidden == false){
        document.querySelector('.group_table').hidden = true;
        document.getElementById('group_table_button').innerHTML = "Show <b>Course Data</b>";
        document.getElementById('group_table_button').className = 'btn-primary mb-2 mr-2 p-2';
    } else {    
        document.querySelector('.group_table').hidden = false;
        document.getElementById('group_table_button').innerHTML = "Hide <b>Course Data</b>";
        document.getElementById('group_table_button').className = 'btn-secondary mb-2 mr-2 p-2';
    }
}