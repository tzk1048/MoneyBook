const ShopListbtn = document.getElementById('shoplistset');

ShopListbtn.addEventListener("click",function(){
    document.getElementById("shoplistform").innerHTML="<form action='' method='post' id='shopcon'><td><input type='submit' form='shopcon' name='shopconset' value='更新'></td><td><input type='text' form='shopcon' name='shopcon'></td></form>";
});

var today = new Date();
today.setDate(today.getDate());
var yyyy = today.getFullYear();
var mm = ("0"+(today.getMonth()+1)).slice(-2);
var dd = ("0"+today.getDate()).slice(-2);
var createday=yyyy+'-'+mm+'-'+dd;

const BankListbtn = document.getElementById('banklistset');

BankListbtn.addEventListener("click",function(){
    document.getElementById("banklistform").innerHTML="<form action='' method='post' id='bank'><td><input type='submit' form='bank' name='bankset' value='更新'></td><td><input type='text' form='bank' name='bank'></td><td><input type='text' form='bank' name='balance'></td><td><input type='date' form='bank' name='createday' value='"+createday+"'></td></form>";
});

const CreditListbtn = document.getElementById('creditlistset');

CreditListbtn.addEventListener("click",function(){
    document.getElementById("creditlistform").innerHTML="<form action='' method='post' id='credit'><td><input type='submit' form='credit' name='creditset' value='更新'></td><td><input type='text' form='credit' name='credit'></td><td><input type='number' min='1' max='31' form='credit' name='dead'></td><td><input type='number' min='1' max='31' form='credit' name='debit'></td><td><select form='credit' name='bankid' id='bankselect'>"+document.getElementById('selectbank').innerHTML+"</select></td></form>";
});

const ChokinListbtn = document.getElementById('chokinlistset');

ChokinListbtn.addEventListener("click",function(){
    const tbl_id=document.getElementById('chokin_table_option').value;
    var row = document.getElementById("chokinlist"+tbl_id).insertRow(-1);
    row.innerHTML="<form action='' method='post' id='chokin'><input type='hidden' form='chokin' name='tblid' value="+tbl_id+"><td><input type='submit' form='chokin' name='chokinset' value='更新'></td><td><input type='text' form='chokin' name='chokin'></td><td><input type='number' form='chokin' name='price'></td></form>";
});

const dbtbl = document.getElementById('Shop_ContentsList');

var updtf=true;

const updatepayment =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText+id;
        var conname = udrow.cells[1].innerText;
        document.getElementById('test3').innerText=conname;
        udrow.cells[1].innerHTML="<input form='updtsc' type='text' name='shopcon' value='" + conname +  "'>";
        udrow.cells[2].innerHTML="<form method='post' action='' id='updtsc'><input type='hidden' name='id' form='updtsc' value='" + id + "'><input type='submit' name='updtdb_sc' form='updtsc' value='更新' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const updatebank =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText+id;
        var conname = udrow.cells[1].innerText;
        var balance = udrow.cells[2].innerText;
        var createday = udrow.cells[3].innerText;
        document.getElementById('test3').innerText=conname;
        udrow.cells[1].innerHTML="<input form='updtb' type='text' name='bank' value='" + conname +  "'>";
        udrow.cells[2].innerHTML="<input form='updtb' type='text' name='balance' value='" + balance +  "'>";
        udrow.cells[3].innerHTML="<input form='updtb' type='date' name='date' value='" + createday +  "'>";
        udrow.cells[4].innerHTML="<form method='post' action='' id='updtb'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb_ba' value='更新' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const updatecredit =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText+id;
        var name = udrow.cells[1].innerText;
        var dead = udrow.cells[2].innerText;
        var debit = udrow.cells[3].innerText;
        var bank = udrow.cells[4].id;
        udrow.cells[1].innerHTML="<input form='updtb' type='text' name='credit' value='" + name +  "'>";
        udrow.cells[2].innerHTML="<input form='updtb' type='number' min='1' max='31' name='dead' value='" + dead +  "'>";
        udrow.cells[3].innerHTML="<input form='updtb' type='number' min='1' max='31' name='debit' value='" + debit +  "'>";
        udrow.cells[4].innerHTML="<select form='updtb' name='bankid' id='updtchokinbank'>"+document.getElementById('selectbank').innerHTML+"</select>";
        document.getElementById('updtchokinbank').options[bank-101].setAttribute("selected", "selected");
        udrow.cells[5].innerHTML="<form method='post' action='' id='updtb'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb_cre' value='更新' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const updatechokin =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText+id;
        var name = udrow.cells[1].innerText;
        var price = udrow.cells[2].innerText;
        udrow.cells[1].innerHTML="<input form='updtb' type='text' name='chokin' value='" + name +  "'>";
        udrow.cells[2].innerHTML="<input form='updtb' type='number' name='price' value='" + price +  "'>";
        udrow.cells[3].innerHTML="<form method='post' action='' id='updtb'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb_cho' value='更新' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const chokin_table_id=document.getElementById('chokin_table_option').value;
document.getElementById('chokinlist'+chokin_table_id).style.display="block";

const chokin_set_table=document.getElementsByClassName('chokin_set_table');

document.getElementById('chokin_table_option').addEventListener('change', (event) => {
    for (let index = 1; index < 10; index++) {
        document.getElementById('chokinlist'+index).style.display="none";
    }
    document.getElementById('chokinlist'+event.target.value).style.display="block";
  });

const chokinpay = document.getElementById('chokinpay').value;
document.getElementById('selectchokinpay').options[chokinpay-101].setAttribute("selected", "selected");