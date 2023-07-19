function headerClickedQuery(string, integer){
    //Below is where the ascending span is editing depending on what heading was clicked
    const thead = document.getElementById(string+"_thead")
    let headers = thead.querySelectorAll('tr')[0].querySelectorAll('th')
    let orderChange = 'no'
    let order = 'asc'
    for(let i = 0; i < headers.length; i++){
        if(headers[i].getAttribute('sort') == 'asc'){
            if(i == integer){
                headers[i].setAttribute('sort', 'desc')
                document.getElementById(string+"_thead_"+i).innerHTML = '&darr;'
                order = 'desc'
            } else{
                headers[i].setAttribute('sort','')
                document.getElementById(string+"_thead_"+i).innerHTML = ''
                orderChange = 'yes'
                order = 'asc'
            }
        } else if(headers[i].getAttribute('sort') == 'desc'){
            if(i == integer){
                headers[i].setAttribute('sort', 'asc')
                document.getElementById(string+"_thead_"+i).innerHTML = '&uarr;'
                order = 'asc'
            } else {
                headers[i].setAttribute('sort','')
                document.getElementById(string+"_thead_"+i).innerHTML = ''
                orderChange = 'yes'
                order = 'asc'
            }
        }
    }
    if(orderChange === 'yes'){
        headers[integer].setAttribute('sort', 'asc')
        document.getElementById(string+"_thead_"+integer).innerHTML = '&uarr;'
    }
    //Below is the retrieval of the data to be sorted
    const tbody = document.getElementById(string+"_tbody")
    const rows = tbody.querySelectorAll('tr')
    let array = []
    let pos 
    for(let i = 0; i < rows.length; i++){
        const singleRow = rows[i].querySelectorAll('td')
        let tempArray = []
        for(let y = 0; y < singleRow.length; y++){
            if(y < 6){
                tempArray.push(singleRow[y].innerText)
            } else {
                let changed = false
                if(singleRow[y].querySelectorAll('input')[0].getAttribute('changed') != null){
                    changed = true
                }
                tempArray.push([singleRow[y].querySelectorAll('input')[0].value, singleRow[y].querySelectorAll('input')[1].getAttribute('value'), singleRow[y].querySelectorAll('input')[1].getAttribute('posval'), changed, singleRow[y].querySelectorAll('input')[0].getAttribute('style'), singleRow[y].querySelectorAll('p')[0].innerText, singleRow[y].querySelectorAll('p')[0].getAttribute('style')])
                pos = 6
            }
        }
        if(pos === integer){
            pos = 0
        }
        const tempData = tempArray[0]
        tempArray[0] = tempArray[integer]
        tempArray[integer] = tempData
        array.push(tempArray)
    }
    //The array of data is sorted
    if(order === 'asc'){
        if(pos === 0){
            array.sort(function(a,b){
                let x = a[0][0]
                let y = b[0][0]
                if(x < y){return -1;}
                if(x > y){return 1;}
                return 0;
            })
        } else {
            array.sort(function(a,b){
                let x = a[0]
                let y = b[0]
                if(x < y){return -1;}
                if(x > y){return 1;}
                return 0;
            })
        }
    } else if(order === 'desc'){
        array.reverse()
    }
    //The data is reoreded to the original positions
    let secondArray = []
    for(let i = 0; i < array.length; i++){
        let tempArray = []
        for(let y = 0; y < array[i].length; y++){
            if(pos === y){
                if(array[i][y][3] === true){
                    tempArray.push([`<input type='text' value='${array[i][y][0]}' id='company${array[i][y][2]}' name='company${array[i][y][2]}' onchange='companyChange(${array[i][y][2]})' class='company${array[i][y][1]}' changed='true' style='${array[i][y][4]}'> <input hidden value='${array[i][y][1]}' name='id${array[i][y][2]}' id='id${array[i][y][2]}' posval='${array[i][y][2]}'> <p style='${array[i][y][6]}' class='bold text-danger' id='company${array[i][y][1]}error'>${array[i][y][5]}</p>`, array[i][y][1]])
                } else {
                    tempArray.push([`<input type='text' value='${array[i][y][0]}' id='company${array[i][y][2]}' name='company${array[i][y][2]}' onchange='companyChange(${array[i][y][2]})' class='company${array[i][y][1]}'> <input hidden value='${array[i][y][1]}' name='id${array[i][y][2]}' id='id${array[i][y][2]}' posval='${array[i][y][2]}'> <p style='${array[i][y][6]}' class='bold text-danger' id='company${array[i][y][1]}error'>${array[i][y][5]}</p>`, array[i][y][1]])
                }
            } else {
                tempArray.push(array[i][y])
            }
        }
        const tempData = tempArray[integer]
        tempArray[integer] = tempArray[0]
        tempArray[0] = tempData
        secondArray.push(tempArray)
    }
    array = secondArray
    //Data from array is put into html and then added to the table
    let innerHTML = '';
    for(let i = 0; i < array.length; i++){
        innerHTML += '<tr>'
        for(let y = 0; y < array[i].length; y++){
            if(y == 1){
                innerHTML += `<td><a href="./../../user/view.php?id=${array[i][6][1]}">${array[i][y]}</a></td>`
            } else if(y < 6){
                innerHTML += '<td>'+array[i][y]+'</td>'
            } else {
                innerHTML += '<td style="display: flex;">'+array[i][y][0]+'</td>'
            }
        }
        innerHTML += '</tr>'
    }
    tbody.innerHTML = innerHTML
}   