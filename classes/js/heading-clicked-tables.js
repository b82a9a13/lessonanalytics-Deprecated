function headerClicked(string, integer){
    //Get headings and determine which heading was clicked and change the header accordingly
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
            } else {
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
                headers[i].setAttribute('sort', '')
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
    //Get all the data from the table and put into an array for later use and log which fields contains dates
    const tbody = document.getElementById(string+"_tbody")
    const rows = tbody.querySelectorAll('tr')
    let datePos = []
    let array = []
    let linkPos = []
    for(let i = 0; i < rows.length; i++){
        const singleRow = rows[i].querySelectorAll('td')
        let tempArray = []
        for(let y = 0; y < singleRow.length; y++){
            if(/[0-9]/.test(singleRow[y].innerText) === true && singleRow[y].innerText.includes('/') === true && /[a-zA-Z]/.test(singleRow[y].innerText) === false){
                const tempString = singleRow[y].innerText.split('/')
                tempArray.push(new Date(tempString[1]+"/"+tempString[0]+"/"+tempString[2]).getTime())
                if(datePos.includes(y) === false && datePos.includes(0) === false){
                    datePos.push(y)
                }
            } else if(singleRow[y].innerText.includes('N/A') === true){
                tempArray.push(0)
                if(datePos.includes(y) === false && datePos.includes(0) === false){
                    datePos.push(y)
                }
            }else if(singleRow[y].innerHTML.includes('</a>') === true){
                tempArray.push([singleRow[y].innerText, singleRow[y].querySelector('a').getAttribute('href')])
                if(linkPos.includes(y) === false && linkPos.includes(0) === false){
                    linkPos.push(y)
                }
            }else {
                tempArray.push(singleRow[y].innerHTML)
            }
        }
        if(datePos.includes(integer) === true && datePos.includes(0) === false){
            datePos[datePos.indexOf(integer)] = 0;
        }
        if(linkPos.includes(integer) === true && linkPos.includes(0) === false){
            linkPos[linkPos.indexOf(integer)] = 0;
        }
        const tempData = tempArray[0]
        tempArray[0] = tempArray[integer]
        tempArray[integer] = tempData
        array.push(tempArray)
    }
    //Sort the array
    if(order === 'asc'){
        if(/[0-9]/.test(array[0][0]) === true && /[a-zA-Z]/.test(array[0][0]) === false){
            array.sort(function(a,b){return a[0].toString()-b[0].toString()})
        } else if(array[0][0].length === 2 && array[0][1].includes('window.location.href') === true){
            array.sort(function(a,b){
                let x = a[0][0];
                let y = b[0][0];
                if(x < y){return -1;}
                if(x > y){return 1;}
                return 0;
            })
        }else {
            array.sort(function(a,b){
                let x = a[0];
                let y = b[0];
                if(x < y){return -1;}
                if(x > y){return 1;}
                return 0;
            })
        }
    } else if(order === 'desc'){
        array.reverse()
    }
    //Change unix timestamp to UK short date
    let secondArray = []
    for(let i = 0; i < array.length; i++){
        let tempArray = []
        for(let y = 0; y < array[i].length; y++){
            if(datePos.includes(y) === true){
                let dateTime = (new Date(array[i][y]).toLocaleDateString('en-GB'))
                if(array[i][y] === 0){
                    dateTime = 'N/A'
                }
                tempArray.push(dateTime)
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
    //Replace table data with sorted data
    let innerHTML = '';
    for(let i = 0; i < array.length; i++){
        innerHTML += '<tr>'
        for(let y = 0; y < array[i].length; y++){
            if(array[i][y].length === 2 && array[i][y][1].includes('window.location.href') === true){
                innerHTML += '<td scope="row" class="px-2"><a href="'+array[i][y][1]+'">'+array[i][y][0]+'</a></td>'
            } else {
                innerHTML += '<td scope="row" class="px-2">'+array[i][y]+'</td>'
            }
        }
        innerHTML += '</tr>'
    }
    tbody.innerHTML = innerHTML
}