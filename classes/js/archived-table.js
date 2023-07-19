//Variables for later use
let checking = [
    ['username', 'fusername'],
    ['lastname', 'flastname'],
    ['firstname', 'ffirstname'],
    ['email', 'femail'],
    ['postcode', 'postcode'],
    ['ulnumber', 'ulnumber'],
    ['lrnumber', 'lrnumber'],
    ['asnumber', 'asnumber'],
    ['lareference', 'lareference'],
    ['latitle', 'latitle'],
    ['standardcode', 'standardcode'],
    ['compstatus', 'compstatus']
]
const border = '2px solid red';
const defaultborder = '1px solid black';
const datecheck = ['dob', 'lstartdate', 'lpenddate', 'lstartdateto', 'lpenddateto'];
let pages = 0;
let page = 0;
let extra = 0;
let csvContent = 'data:text/csv;charset=utf-8,'
//Called when the form is submitted
document.getElementById('archivedform').addEventListener('submit', function(e){
    e.preventDefault();
    const fusername = document.getElementById('fusername').value;
    const flastname = document.getElementById('flastname').value;
    const ffirstname = document.getElementById('ffirstname').value;
    const femail = document.getElementById('femail').value;
    const postcode = document.getElementById('postcode').value;
    const dob = document.getElementById('dob').value;
    const ulnumber = document.getElementById('ulnumber').value;
    const lrnumber = document.getElementById('lrnumber').value;
    const asnumber = document.getElementById('asnumber').value;
    const lareference = document.getElementById('lareference').value;
    const latitle = document.getElementById('latitle').value;
    const standardcode = document.getElementById('standardcode').value;
    const lstartdate = document.getElementById('lstartdate').value;
    const lpenddate = document.getElementById('lpenddate').value;
    const compstatus = document.getElementById('compstatus').value;
    const lstartdateto = document.getElementById('lstartdateto').value;
    const lpenddateto = document.getElementById('lpenddateto').value;
    const fparams = `fusername=${fusername}&flastname=${flastname}&ffirstname=${ffirstname}&femail=${femail}&postcode=${postcode}&dob=${dob}&ulnumber=${ulnumber}&lrnumber=${lrnumber}&asnumber=${asnumber}&lareference=${lareference}&latitle=${latitle}&standardcode=${standardcode}&lstartdate=${lstartdate}&lpenddate=${lpenddate}&compstatus=${compstatus}&lstartdateto=${lstartdateto}&lpenddateto=${lpenddateto}`;
    const fxhr = new XMLHttpRequest();
    fxhr.open('POST', './classes/inc/archivedusers.inc.php', true);
    fxhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    fxhr.onload = function(){
        if(this.status == 200){
            const text = JSON.parse(this.responseText);
            let error = false;
            for(let i = 0; i < checking.length; i++){
                if(text[checking[i][0]] != null){
                    if(text[checking[i][0]][0] == true){
                        document.getElementById(checking[i][1]).style.border = border;
                        document.getElementById(checking[i][1]+"error").innerText = "Invalid Characters: "+text[checking[i][0]][1];
                        error = true;
                    }
                } else {
                    document.getElementById(checking[i][1]).style.border = defaultborder;
                    document.getElementById(checking[i][1]+"error").innerText = "";
                }
            }
            for(let i = 0; i < datecheck.length; i++){
                if(text[datecheck[i]] == true){
                    document.getElementById(datecheck[i]).style.border = border;
                    error = true;
                } else {
                    document.getElementById(datecheck[i]).style.border = defaultborder;
                }
            }
            if(error == false){
                if(text.length != 0){
                    document.getElementById('archiveerror').style.display = 'none';
                    let output = '';
                    let csvPos = 1;
                    let csvOutput = [['id', 'Username', 'Lastname', 'Firstname', 'Email', 'Aim Sequence Number', 'Learning Aim Reference', 'Learning Aim Title', 'Learning Start Date', 'Learning Planned End Date', 'Competion State', 'Standard Code', 'Postcode', 'Date of Birth', 'Unique Learner Number', 'Learner Reference Number']];
                    for(i = 0; i < text.length; i++){
                        let style = '';
                        if(i > 9){
                            style = 'display: none;'
                        }
                        output += `
                        <tr style="${style}" class="archived-tr">
                            <td class="archived-tr-td">${text[i][1]}</td>
                            <td class="archived-tr-td"><a href="./../../user/view.php?id=${text[i][0]}">${text[i][2]}</a></td>
                            <td class="archived-tr-td">${text[i][3]}</td>
                            <td class="archived-tr-td">${text[i][4]}</td>
                            <td class="archived-tr-td">${text[i][5]}</td>
                            <td class="archived-tr-td">
                            <table>
                        `;
                        if(text[i][6]){
                            let y = 0;
                            while(y < text[i][6].length){
                                let tempcsv = []
                                tempcsv[0] = csvPos
                                csvPos++
                                tempcsv[1] = text[i][2]
                                tempcsv[2] = text[i][3]
                                tempcsv[3] = text[i][4]
                                tempcsv[4] = text[i][5]
                                output += `<tr>`;
                                let t = 0;
                                while(t < text[i][6][y].length){
                                    output += `<td style="width: 14%;">${text[i][6][y][t]}</td>`;
                                    if(t === 2){
                                        tempcsv[5+t] = text[i][6][y][t].replaceAll(',','.')
                                    } else {
                                        tempcsv[5+t] = text[i][6][y][t]
                                    }
                                    t++;
                                }
                                output += `</tr>`;
                                tempcsv[12] = text[i][7]
                                tempcsv[13] = text[i][8]
                                tempcsv[14] = text[i][9]
                                tempcsv[15] = text[i][10]
                                csvOutput.push(tempcsv)
                                y++;
                            }
                        }
                        output +=`
                            </table>
                            </td>
                            <td class="archived-tr-td">${text[i][7]}</td>
                            <td class="archived-tr-td">${text[i][8]}</td>
                            <td class="archived-tr-td">${text[i][9]}</td>
                            <td class="archived-tr-td">${text[i][10]}</td>
                        </tr>`;
                    }
                    csvContent = 'data:text/csv;charset=utf-8,'
                    csvOutput.forEach(function(rows){
                        let row = rows.join(",")
                        csvContent += row + "\r\n"
                    })
                    pages = Math.ceil(text.length / 10);
                    extra = text.length % 10;
                    page = 0;
                    document.getElementById('archivetable_tbody').innerHTML = output;
                    document.getElementById('archivetable').style.display = 'block';
                    document.getElementById('archivetableempty').innerText = '';
                    document.getElementById('pagenum').value = page+1;
                    document.getElementById('paginationtext').innerText = ` /${pages}`;
                } else {
                    document.getElementById('archivetable_tbody').innerHTML = "";
                    document.getElementById('archivetable').style.display = 'none';
                    document.getElementById('archivetableempty').innerText = 'No Search Results';
                }
                document.getElementById('archiveerror').style.display = 'none';
            } else if (error == true){
                document.getElementById('archiveerror').style.display = 'block';
            }
        }
    }
    fxhr.send(fparams);
})
//Resets certain date fields depeding on the input
function resetdate(input){
    for(let i = 0; i < input.length; i++){
        if(i === 1){
            document.getElementById(input[i]).value = null;
            document.getElementById(input[i]).disabled = true;
        } else {
            document.getElementById(input[i]).value = null;
        }
    }
}
//Resets all input fields in the form
function resetfields(){
    for(let i = 0; i < checking.length; i++){
        document.getElementById(checking[i][1]).value = "";
        document.getElementById(checking[i][1]).style.border = defaultborder;
        document.getElementById(checking[i][1]+"error").innerText = "";
    }
    for(let i = 0; i < datecheck.length; i++){
        document.getElementById(datecheck[i]).value = "";
        document.getElementById(datecheck[i]).style.border = defaultborder;
        if(i > 2){
            document.getElementById(datecheck[i]).disabled = true;
        }
    }
    document.getElementById('archiveerror').style.display = 'none';
}
//Called when previous page is clicked
function prevpage(){
    let rows = document.getElementById('archivetable_tbody').rows;
    if(page > 0){
        if(page == pages - 1){
            for(let i = 0; i < extra; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page--;
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = '';
            }
        } else {
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page--;
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = '';
            }
        }
        document.getElementById('pagenum').value = page+1;
        document.getElementById('pagenum').scrollIntoView(false);
    }
    this.pagenumwidth();
}
//Called when next page is clicked
function nextpage(){
    let rows = document.getElementById('archivetable_tbody').rows;
    if(page < pages - 1){
        if(page == pages - 2){
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page++;
            for(let i = 0; i < extra; i++){
                rows[i+(10*page)].style.display = '';
            }
        } else {
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page++;
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = '';
            }
        }
        document.getElementById('pagenum').value = page+1;
        document.getElementById('pagenum').scrollIntoView(false);
    }
    this.pagenumwidth();
}
//Called when pagenum input has changed
document.getElementById('pagenum').addEventListener('input', function(){
    pagenumwidth();
    if(this.value <= pages && this.value > 0 && this.value.length != 0 && !this.value.includes(" ")){
        let rows = document.getElementById('archivetable_tbody').rows;
        if(page == pages - 2){
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page = this.value-1;
            for(let i = 0; i < extra; i++){
                rows[i+(10*page)].style.display = '';
            }
        } else if (page == pages - 1){
            for(let i = 0; i < extra; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page = this.value-1;
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = '';
            }
        }else {
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = 'none';
            }
            page = this.value-1;
            for(let i = 0; i < 10; i++){
                rows[i+(10*page)].style.display = '';
            }
        }
    }
    document.getElementById('pagenum').scrollIntoView(false);
})
//Used to get the pagenum width
function pagenumwidth(){
    document.getElementById('pagenum').style.width = ((document.getElementById('pagenum').value.length + 1)*8)+'px';
}
//function force downloads the data in the table as a csv
function downloadcsv(){
    let encodedUri = encodeURI(csvContent)
    let link = document.createElement("a")
    link.setAttribute("href", encodedUri)
    link.setAttribute("download", 'archivedLearners.csv')
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    //Extra
    downloadedevent()
}
//extra start
function downloadedevent(){
    const fparamss = 'downloaded=true';
    const fxhrr = new XMLHttpRequest();
    fxhrr.open('POST', './classes/inc/archivedcsvdownloaded.inc.php', true);
    fxhrr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    fxhrr.send(fparamss);
}
//extra end
//Called when a date is changed
function dateChange(input){
    const selectedElement = document.getElementById(input).value
    const toElement = document.getElementById(input+"to")
    if(selectedElement != ""){
        toElement.disabled = false;
    } else {
        toElement.disabled = true;
        toElement.value = "";
    }
}
//Used to show more filter options
function archived_show_more(){
    let more = document.getElementById('archived_more_section')
    if(more.style.display === 'none'){
        more.style.display = 'block'
        document.getElementById('archived_show_more').innerText = 'Show less...'
    } else if(more.style.display === 'block') {
        more.style.display = 'none'
        document.getElementById('archived_show_more').innerText = 'Show more...'
    }
}