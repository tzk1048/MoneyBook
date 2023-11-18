const dbtbl = document.getElementById('cuurrent_table');

var updtf=true;

const update_ex =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        var date = udrow.cells[0].innerText;
        var bankid = udrow.cells[1].id;
        var conid = udrow.cells[2].id;
        var price = udrow.cells[3].innerText;
        var comment = udrow.cells[4].innerText;
        udrow.cells[0].innerHTML="<input form='updt' type='date' name='date' value='" + date +  "'>";
        udrow.cells[1].innerHTML=document.getElementById('updttagpay').innerHTML;
        document.getElementById('updttagpay').innerHTML="";
        document.getElementById('updtbankpay').options[bankid%100-1].selected = true;
        udrow.cells[2].innerHTML=document.getElementById('updttagshopcon').innerHTML;
        document.getElementById('updttagshopcon').innerHTML="";
        document.getElementById('updtshopcon').options[conid%10000-1].selected = true;
        udrow.cells[3].innerHTML="<input form='updt' type='number' name='price' value='" + price +  "'>";
        udrow.cells[4].innerHTML="<input form='updt' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[5].id="updtdb_now_td";
        udrow.cells[5].innerHTML="<form method='post' action='' id='updt'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb' value='UPDATE' class='updtdb_now'></form>";
        udrow.cells[6].id="updtdb_now_td";
        udrow.cells[6].innerHTML="<button class='updtdb_now' onclick='location.reload()'>CANCEL</button>"
        updtf=false;
    }
}

const update_cre =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText;
        var date = udrow.cells[0].innerText;
        var bankid = udrow.cells[1].id;
        var conid = udrow.cells[2].id;
        var price = udrow.cells[3].innerText;
        var comment = udrow.cells[4].innerText;
        document.getElementById('test3').innerText=date+","+price+","+bankid+","+conid;
        udrow.cells[0].innerHTML="<input form='updt' type='date' name='date' value='" + date +  "'>";
        udrow.cells[1].innerHTML=document.getElementById('updttagcredit').innerHTML;
        document.getElementById('updttagcredit').innerHTML="";
        document.getElementById('updtbankpay').options[bankid%200-1].selected = true;
        udrow.cells[2].innerHTML=document.getElementById('updttagshopcon').innerHTML;
        document.getElementById('updttagshopcon').innerHTML="";
        document.getElementById('updtshopcon').options[conid%10000-1].selected = true;
        udrow.cells[3].innerHTML="<input form='updt' type='number' name='price' value='" + price +  "'>";
        udrow.cells[4].innerHTML="<input form='updt' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[6].innerHTML="<form method='post' action='' id='updt'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb' value='UPDATE' style='background-color: blue; color: white;'></form>";
        udrow.cells[7].innerHTML="CANCEL"
        updtf=false;
    }
}

const update_income =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText;
        var date = udrow.cells[0].innerText;
        var bankid = udrow.cells[1].id;
        var conid = udrow.cells[2].id;
        var price = udrow.cells[3].innerText;
        var comment = udrow.cells[4].innerText;
        document.getElementById('test3').innerText=date+","+price+","+bankid+","+conid;
        udrow.cells[0].innerHTML="<input form='updt' type='date' name='date' value='" + date +  "'>";
        udrow.cells[1].innerHTML=document.getElementById('updttagpay').innerHTML;
        document.getElementById('updttagpay').innerHTML="";
        document.getElementById('updtbankpay').options[bankid%100-1].selected = true;
        udrow.cells[2].innerHTML=document.getElementById('updttagincome').innerHTML;
        document.getElementById('updttagincome').innerHTML="";
        document.getElementById('updtincome').options[conid%20000-1].selected = true;
        udrow.cells[3].innerHTML="<input form='updt' type='number' name='price' value='" + price +  "'>";
        udrow.cells[4].innerHTML="<input form='updt' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[5].innerHTML="<form method='post' action='' id='updt'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb' value='UPDATE' style='background-color: blue; color: white;'></form>";
        udrow.cells[6].innerHTML="CANCEL"
        updtf=false;
    }
}

const update_chokin =(id)=>{
    if(updtf){
        var udrow =document.getElementById(id);
        var date = udrow.cells[0].innerText;
        var conid = udrow.cells[1].id;
        //var price = udrow.cells[2].innerText;
        var bankid = udrow.cells[3].id;
        var comment = udrow.cells[4].innerText;
        udrow.cells[0].innerHTML="<input form='updt' type='date' name='date' value='" + date +  "'>";
        udrow.cells[1].innerHTML=document.getElementById('updttagchokin').innerHTML;
        document.getElementById('updttagchokin').innerHTML="";
        document.getElementById('updtchokin').options[conid%1000].selected = true;
        udrow.cells[3].innerHTML=document.getElementById('updttagpay').innerHTML;
        document.getElementById('updttagpay').innerHTML="";
        document.getElementById('updtbankpay').options[bankid%100-1].selected = true;
        //udrow.cells[3].innerHTML="<input form='updt' type='number' name='price' value='" + price +  "'>";
        udrow.cells[4].innerHTML="<input form='updt' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[5].innerHTML="<form method='post' action='' id='updt'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtdb' value='UPDATE' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const update_move =(m_id,w_id,d_id)=>{
    if(updtf){
        var udrow =document.getElementById(m_id);
        document.getElementById('test').innerText="test";
        document.getElementById('test2').innerText=dbtbl.rows[0].cells[0].innerText;
        var date = udrow.cells[0].innerText;
        var frombid = udrow.cells[1].id;
        var tobid = udrow.cells[2].id;
        var price = udrow.cells[3].innerText;
        var comment = udrow.cells[4].innerText;
        document.getElementById('test3').innerText=date+","+price;
        udrow.cells[0].innerHTML="<input form='updt' type='date' name='date' value='" + date +  "'>";
        udrow.cells[1].innerHTML=document.getElementById('updttagpay').innerHTML;
        document.getElementById('updttagpay').innerHTML="";
        document.getElementById('updtbankpay').options[frombid%100-1].selected = true;
        udrow.cells[2].innerHTML=document.getElementById('updttagpay2').innerHTML;
        document.getElementById('updttagpay2').innerHTML="";
        document.getElementById('updtbankpay2').options[tobid%100-1].selected = true;
        udrow.cells[3].innerHTML="<input form='updt' type='number' name='price' value='" + price +  "'>";
        udrow.cells[4].innerHTML="<input form='updt' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[5].innerHTML="<form method='post' action='' id='updt'><input type='hidden' name='id' value='" + m_id + "'><input type='hidden' name='from_id' value='" + w_id + "'><input type='hidden' name='to_id' value='" + d_id + "'><input type='submit' name='updtdb' value='UPDATE' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}