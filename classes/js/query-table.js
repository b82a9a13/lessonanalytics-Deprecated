let checkVal = [
    'username',
    'surname',
    'firstname',
    'email',
    'city',
    'company'
]
const border = '2px solid red';
const defaultborder = '1px solid black';
document.getElementById('queryform').addEventListener('submit', function(e){
    e.preventDefault();
    let errorText = document.getElementById('querytableerror')
    let successText = document.getElementById('querytablesuccess')
    errorText.style.display = 'none'
    successText.style.display = 'none'
    let username = document.getElementById('username').value;
    let surname = document.getElementById('surname').value;
    let firstname = document.getElementById('firstname').value;
    let email = document.getElementById('email').value;
    let city = document.getElementById('city').value;
    let company = document.getElementById('company').value;
    let params = `username=${username}&surname=${surname}&firstname=${firstname}&email=${email}&city=${city}&company=${company}`;
    let xhr = new XMLHttpRequest();
    xhr.open('POST', './classes/inc/query.inc.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function(){
        if(this.status == 200){
            let text = JSON.parse(this.responseText);
            let error = false;
            for(let i = 0; i < checkVal.length; i++){
                if(text[checkVal[i]] != null){
                    if(text[checkVal[i]][0] === true){
                        document.getElementById(checkVal[i]).style.border = border;
                        if(i === 3){
                            document.getElementById(checkVal[i]+"error").innerText = "Invalid Email";
                        } else {
                            document.getElementById(checkVal[i]+"error").innerText = "Invalid: "+text[checkVal[i]][1];
                        }
                        error = true;
                    }
                } else{
                    document.getElementById(checkVal[i]).style.border = defaultborder;
                    document.getElementById(checkVal[i]+"error").innerText = '';
                }
            }
            let queryUpdate = document.getElementById('queryupdate');
            if(error === false){
                if(text.length != 0){
                    queryUpdate.style.display = 'none';
                    let output = '';
                    for(let i = 0; i < text.length; i++){
                        let companyVal = '';
                        if(text[i][6][1]){
                            companyVal = 'value="'+text[i][6][1]+'"';
                        }
                        output += `
                        <tr>
                            <td>${text[i][0][1]}</td>
                            <td><a href="./../../user/view.php?id=${text[i][7][1]}">${text[i][1][1]}</a></td>
                            <td>${text[i][2][1]}</td>
                            <td>${text[i][3][1]}</td>
                            <td>${text[i][4][1]}</td>
                            <td>${text[i][5][1]}</td>
                            <td style="display: flex;">
                                <input type="text" ${companyVal} value="${text[i][6][1]}" id="company${text[i][0][1]}" name="company${text[i][0][1]}" onchange="companyChange(${text[i][0][1]})" class="company${text[i][7][1]}">
                                <input hidden value="${text[i][7][1]}" name="id${text[i][0][1]}" id="id${text[i][0][1]}" posval="${text[i][0][1]}">
                                <p style="display: none; margin-left: .25rem;" class="bold text-danger" id="company${text[i][7][1]}error"></p>
                            </td>
                        </tr>`
                    }
                    document.getElementById('querytable').style.display = 'block';
                    document.getElementById('querytable_tbody').innerHTML = output;
                    document.getElementById('total').setAttribute('value', text.length);
                } else {
                    queryUpdate.innerText = 'No search results'
                    queryUpdate.className = 'bold text-danger';
                    queryUpdate.style.display = 'block';
                    document.getElementById('querytable').style.display = 'none';
                }
            } else if(error === true){
                queryUpdate.innerText = 'Invalid inputs are highlighted in red';
                queryUpdate.className = 'bold text-danger';
                queryUpdate.style.display = 'block';
                document.getElementById('querytable').style.display = 'none';
            }
        }
    }
    xhr.send(params)
})
document.getElementById('querytable').addEventListener("submit", function(e){
    e.preventDefault()
    let value = document.getElementById('total').value
    let params = ''
    let companyPos = 1
    for(let i = 1; i <= value; i++){
        let companyVal = document.getElementById('company'+i)
        let idVal = document.getElementById('id'+i).value
        if(i == value && companyVal.getAttribute('changed') == 'true'){
            params += '&company'+companyPos+'='+companyVal.value+'&id'+companyPos+'='+idVal
            companyPos++
        } else if(companyVal.getAttribute('changed') == 'true'){
            params += '&company'+companyPos+'='+companyVal.value+'&id'+companyPos+'='+idVal
            companyPos++
        }
    }
    params = 'total='+String(companyPos-1) + params
    if(companyPos != 1){
        let xhr = new XMLHttpRequest()
        xhr.open('POST', './classes/inc/update.inc.php', true)
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
        xhr.onload = function(){
            if(this.status == 200){
                let text = JSON.parse(this.responseText)
                let error = false;
                let correct = false;
                if(text['errors'] != null){
                    let company = text['errors']['company']
                    for(i = 0; i < company.length; i++){
                        if(company[i][1] == true){
                            document.querySelector('.company'+company[i][0]).style.border = border
                            error = true
                            document.getElementById('company'+company[i][0]+'error').innerText = 'Invalid: '+company[i][2]
                            document.getElementById('company'+company[i][0]+'error').style.display = 'block'
                        }
                    }
                }
                if(text['success'] != null){
                    let company = text['success']['company']
                    for(i = 0; i < company.length; i++){
                        if(company[i][1] == true){
                            document.querySelector('.company'+company[i][0]).style.border = '2px solid green'
                            correct = true
                            document.getElementById('company'+company[i][0]+'error').style.display = 'none'
                        }
                    }
                }
                let errorText = document.getElementById('querytableerror')
                let successText = document.getElementById('querytablesuccess')
                if(correct == true){
                    successText.innerText = 'Updated'
                    successText.style.display = 'block'
                } else if (correct == false){
                    successText.style.display = 'none'
                }
                if(error == true){
                    errorText.innerText = 'Invalid inputs are highlighted in red'
                    errorText.style.display = 'block'
                } else if (error == false){
                    errorText.style.display = 'none'
                }
            }
        }
        xhr.send(params)
    }
})
function companyChange(id){
    document.getElementById('company'+id).setAttribute('changed', 'true')
}