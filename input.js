//inputコピー
document.getElementById('current_table_insert_btn').innerHTML="<form action='' method='post'><input type='submit' name='insertdb' value='INSERT' id='insertdb'></form>"

const InputCashClear =()=> {
    var input_form = document.getElementsByClassName('input_form');
    for (let index = 0; index < input_form.length; index++) {
        input_form[index].value="";
        
    }
}

const Show =(content,button,text)=>  {
    const ctn=document.getElementById(content);
    const btn=document.getElementById(button);
    if(btn.innerText==text) {
        btn.innerText=btn.value;
        ctn.style.display="table";
    }  else {
        btn.value=btn.innerText;
        btn.innerText=text;
        ctn.style.display="none";
    }
}

const Small =(content,button,text)=>  {
    //const ctn=document.getElementById(content);
    const btn=document.getElementById(button);
    if(btn.innerText==text) {
        btn.innerText=btn.value;
        document.getElementsByClassName('main-top')[0].style.height="50%";
    }  else {
        btn.value=btn.innerText;
        btn.innerText=text;
        document.getElementsByClassName('main-top')[0].style.height="200px";
    }
}

//inputコピー終わり

//↓↓input_paymentコピー

const tbl=document.getElementById("payment_list");

const inputcash_ConID = document.getElementById("inputcash_ConID");


const selectShop_Contents = (id) => {
    document.getElementById("inputcash_ConID").value = id-10000;
    var rowid = id-10000;
    var index = tbl.rows.length-1;
    if(rowid<=index) {
        rowvalue = tbl.rows[rowid].cells[0].innerText;
        index = tbl.rows[rowid].cells[0].innerText;
    } else {
        rowvalue = tbl.rows[index].cells[0].innerText;
    }
    while (rowid!=rowvalue) {
        index--;
        rowvalue = tbl.rows[index].cells[0].innerText;
    }
    document.getElementById("inputcash_ConName").value = tbl.rows[index].cells[1].innerText;
}

inputcash_ConID.addEventListener("change",function(){
    document.getElementById("inputcash_ConName").value = tbl.rows[inputcash_ConID.value].cells[1].innerText;
});

//↑↑input_paymentコピー終わり

//↓↓inputコピー
window.onload = function() {
    var today =  new Date();
    today.setDate(today.getDate());
    var yyyy = today.getFullYear();
    var mm = ("0" + (today.getMonth() + 1)).slice(-2);
    var dd = ("0" + today.getDate()).slice(-2);
    document.getElementById("inputcash_date").value = yyyy + "-" + mm + "-" + dd;
}
//↑↑inputコピー終わり

const credit_debit_id=document.getElementById('selecttag_credit_debit').value;
document.getElementById('creditdebit'+credit_debit_id).style.display="block";

document.getElementById('selecttag_credit_debit').addEventListener('change', (event) => {
    for (let index = 1; index < 10; index++) {
        document.getElementById('creditdebit'+index).style.display="none";
    }
    document.getElementById('creditdebit'+event.target.value).style.display="block";
  });
  