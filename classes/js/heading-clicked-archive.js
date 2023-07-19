function headerClickedArchive(string, integer){
    //Below is code for changing the ascending span depending on what heading was clicked
    const thead = document.getElementById(string+"_thead")
    let headers = thead.querySelectorAll('tr')[0].querySelectorAll('th')
    let orderChange = 'no'
    let order = 'asc'
    for(let i = 0; i < 16; i++){
        if(i >= 5 && i < 12){
            const tempElem = document.getElementById(string+'_multi').querySelector('tbody').querySelector('tr').querySelectorAll('th')[i-5]
            if(tempElem.getAttribute('sort') == 'asc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'desc')
                    document.getElementById(string+'_thead_'+i).innerHTML = '&darr;'
                    order = 'desc'
                } else{
                    tempElem.setAttribute('sort', '')
                    document.getElementById(string+'_thead_'+i).innerHTML = ''
                    orderChange ='yes'
                    order = 'asc'
                }
            } else if(tempElem.getAttribute('sort') == 'desc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'asc')
                    document.getElementById(string+"_thead_"+i).innerHTML = '&uarr;'
                    order = 'asc'
                } else {
                    tempElem.setAttribute('sort','')
                    document.getElementById(string+"_thead_"+i).innerHTML = ''
                    orderChange = 'yes'
                    order = 'asc'
                }
            }
        } else if(i >= 12){
            const tempElem = headers[i+1]
            if(tempElem.getAttribute('sort') == 'asc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'desc')
                    document.getElementById(string+"_thead_"+i).innerHTML = '&darr;'
                    order = 'desc'
                } else {
                    tempElem.setAttribute('sort','')
                    document.getElementById(string+"_thead_"+i).innerHTML = ''
                    orderChange = 'yes'
                    order = 'asc'
                }
            } else if(tempElem.getAttribute('sort') == 'desc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'asc')
                    document.getElementById(string+"_thead_"+i).innerHTML = '&uarr;'
                    order = 'asc'
                } else {
                    tempElem.setAttribute('sort','')
                    document.getElementById(string+"_thead_"+i).innerHTML = ''
                    orderChange = 'yes'
                    order = 'asc'
                }
            }
        } else {
            const tempElem = headers[i]
            if(tempElem.getAttribute('sort') == 'asc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'desc')
                    document.getElementById(string+"_thead_"+i).innerHTML = '&darr;'
                    order = 'desc'
                } else {
                    tempElem.setAttribute('sort','')
                    document.getElementById(string+"_thead_"+i).innerHTML = ''
                    orderChange = 'yes'
                    order = 'asc'
                }
            } else  if(tempElem.getAttribute('sort') == 'desc'){
                if(i == integer){
                    tempElem.setAttribute('sort', 'asc')
                    document.getElementById(string+"_thead_"+i).innerHTML = '&uarr;'
                    order = 'asc'
                } else {
                    tempElem.setAttribute('sort', '')
                    document.getElementById(string+"_thead_"+i).innerHTML = ''
                    orderChange = 'yes'
                    order = 'asc'
                }
            }
        }
    }
    if(orderChange === 'yes'){
        if(integer >= 5 && integer < 12){
            document.getElementById(string+"_multi").querySelector('tbody').querySelector('tr').querySelectorAll('th')[integer-5].setAttribute('sort', 'asc')
        } else if(integer >= 12){
            headers[integer+1].setAttribute('sort','asc')
        } else {
            headers[integer].setAttribute('sort','asc')
        }
        document.getElementById(string+"_thead_"+integer).innerHTML = '&uarr;'
    }
    //Below is where all the data from the table is put into an array
    const tbody = document.getElementById(string+"_tbody")
    const rows = tbody.querySelectorAll('.archived-tr')
    let array = []
    let datePos
    let datesPos = []
    for(let i = 0; i < rows.length; i++){
        const singleRow = rows[i].querySelectorAll('.archived-tr-td')
        let tempArray = []
        for(let y = 0; y < singleRow.length; y++){
            if(y === 1){
                tempArray.push([singleRow[y].querySelector('a').innerText, singleRow[y].querySelector('a').href])
            } else if (y === 5){
                let tempArr = []
                const currentRow = singleRow[y].querySelectorAll('tr')
                for(let j = 0; j < currentRow.length; j++){
                    let currentArray = []
                    for(let l = 0; l < currentRow[j].querySelectorAll('td').length; l++){
                        if(l === 3 || l === 4){
                            if(/[0-9]/.test(currentRow[j].querySelectorAll('td')[l].innerText) === true && currentRow[j].querySelectorAll('td')[l].innerText.includes('-') === true && /[a-zA-Z]/.test(currentRow[j].querySelectorAll('td')[l].innerText) === false){
                                let tempString = currentRow[j].querySelectorAll('td')[l].innerText.split('-')
                                currentArray.push(new Date(tempString[1]+'/'+tempString[0]+'/'+tempString[2]).getTime())
                                if(datesPos.includes(l) === false && datesPos.includes(0) === false){
                                    datesPos.push(l)
                                }
                            }
                        } else {
                            currentArray.push(currentRow[j].querySelectorAll('td')[l].innerText)
                        }
                    }
                    if(integer >= 5 && integer < 12){
                        const tempData = currentArray[0]
                        currentArray[0] = currentArray[integer-5]
                        currentArray[integer-5] = tempData
                    }
                    tempArr.push(currentArray)
                }
                tempArray.push(tempArr)
            } else if (y === 7){
                if(/[0-9]/.test(singleRow[y].innerText) === true && singleRow[y].innerText.includes('-') === true && /[a-zA-Z]/.test(singleRow[y].innerText) === false){
                    let tempString = singleRow[y].innerText.split('-')
                    tempArray.push(new Date(tempString[1]+'/'+tempString[0]+'/'+tempString[2]).getTime())
                    if(datePos != y && datePos != 0){
                        datePos = y
                    }
                }
            } else {
                tempArray.push(singleRow[y].innerText)
            }
        }
        if(datePos == integer-6){
            datePos = 0
        }
        if(datesPos.includes(integer-5) && datesPos.includes(0) === false){
            datesPos[datesPos.indexOf(integer-5)] = 0;
        }
        if(integer < 5){
            const tempData = tempArray[0]
            tempArray[0] = tempArray[integer]
            tempArray[integer] = tempData
        } else if(integer > 11){
            const tempData = tempArray[0]
            tempArray[0] = tempArray[integer-6]
            tempArray[integer-6] = tempData
        }
        array.push(tempArray)
    }
    //Here is where the sorting on the array dependant on what heading is clicked
    if(order === 'asc'){
        if(integer < 5 || integer > 11){
            if(/[0-9]/.test(array[0][0]) === true && /[a-zA-Z]/.test(array[0][0]) === false){
                array.sort(function(a,b){return a[0].toString()-b[0].toString()})
            } else {
                array.sort(function(a,b){
                    let x = a[0];
                    let y = b[0];
                    if(x < y){return -1;}
                    if(x > y){return 1;}
                    return 0;
                })
            }
        } else if(integer >= 5 && integer < 12){
            for(let i = 0; i < array.length; i++){
                if(/[0-9]/.test(array[i][5][0]) === true && /[a-zA-Z]/.test(array[i][5][0]) === false){
                    array[i][5].sort(function(a,b){return a[0].toString()-b[0].toString()})
                } else {
                    array[i][5].sort(function(a,b){
                        let x = a[0];
                        let y = b[0];
                        if(x < y){return -1;}
                        if(x > y){return 1;}
                        return 0;
                    })
                }
            }
        }
    } else if(order === 'desc'){
        if(integer < 5 || integer > 11){
            array.reverse()
        } else if(integer >= 5 && integer < 12){
            for(let i = 0; i < array.length; i++){
                array[i][5].reverse()
            }
        }
    }
    //Re order array to the correct order and change unix dates to UK date
    let secondArray = []
    for(let i = 0; i < array.length; i++){
        let tempArray = []
        for(y = 0; y < array[i].length; y++){
            if(y === 5){
                let tempArr = []
                const currentPos = array[i][y]
                for(let j = 0; j < currentPos.length; j++){
                    let currentArray = []
                    for(let l = 0; l < currentPos[j].length; l++){
                        if(datesPos.includes(l) === true){
                            let tempDate = (new Date(currentPos[j][l]).toLocaleDateString('en-GB'))
                            tempDate = tempDate.split('/')
                            tempDate = `${tempDate[0]}-${tempDate[1]}-${tempDate[2]}`
                            currentArray.push(tempDate)
                        } else {
                            currentArray.push(currentPos[j][l])
                        }
                    }
                    if(integer >= 5 && integer < 12){
                        const tempData = currentArray[integer-5]
                        currentArray[integer-5] = currentArray[0]
                        currentArray[0] = tempData
                    }
                    tempArr.push(currentArray)
                }
                tempArray.push(tempArr)
            } else if(datePos === y){
                let tempDate = (new Date(array[i][y]).toLocaleDateString('en-GB'))
                tempDate = tempDate.split('/')
                tempDate = `${tempDate[0]}-${tempDate[1]}-${tempDate[2]}`
                tempArray.push(tempDate)
            } else {
                tempArray.push(array[i][y])
            }
        }
        if(integer < 5){
            const tempData = tempArray[integer]
            tempArray[integer] = tempArray[0]
            tempArray[0] = tempData
        } else if(integer > 11){
            const tempData = tempArray[integer-6]
            tempArray[integer-6] = tempArray[0]
            tempArray[0] = tempData
        }
        secondArray.push(tempArray)
    }
    array = secondArray
    //Replace current table data to sorted data
    let innerHTML = ''
    for(let i = 0; i < array.length; i++){
        style = ''
        if(i > 9){
            style = 'display: none;'
        }
        innerHTML += `<tr style="${style}" class="archived-tr">`
        for(let y = 0; y < array[i].length; y++){
            if(y === 1){
                innerHTML += `<td class="archived-tr-td"><a href="${array[i][y][1]}">${array[i][y][0]}</a></td>`
            } else if(y === 5){
                innerHTML += `<td class="archived-tr-td"><table>`
                for(let j = 0; j < array[i][y].length; j++){
                    innerHTML += `<tr>`
                    for(let k = 0; k < array[i][y][j].length; k++){
                        innerHTML += `<td style="width: 14%;">${array[i][y][j][k]}</td>`
                    }
                    innerHTML += `</tr>`
                }
                innerHTML += `</table></td>`
            } else{
                innerHTML += `<td class="archived-tr-td">${array[i][y]}</td>`
            }
        }
        innerHTML += '</tr>'
    }
    tbody.innerHTML = innerHTML
    document.getElementById('pagenum').value = 1
    const pages = Math.ceil(array.length / 10)
    document.getElementById('paginationtext').innerText = ` /${pages}`
}