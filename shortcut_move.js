const listshort_date = document.getElementById('listshort_date');
const listshort_bank = document.getElementById('listshort_bank');
const listshort_bank2 = document.getElementById('listshort_bank2');
const listshort_price = document.getElementById('listshort_price');
const listshort_com = document.getElementById('listshort_com');

listshort_bank.innerHTML = document.getElementById('shorttagpay').innerHTML;
listshort_bank2.innerHTML = document.getElementById('shorttagpay').innerHTML;

const shortcutdate = document.getElementsByClassName('shortcutdate');
const shorttagpay = document.getElementsByClassName('shortcuttagpay');
const shorttagpay2 = document.getElementsByClassName('shortcuttagpay2');
const shortcutprice = document.getElementsByClassName('shortcutprice');
const shortcutcom = document.getElementsByClassName('shortcutcom');
for (let index = 0; index < shorttagpay.length; index++) {
    shorttagpay[index].innerHTML = document.getElementById('shorttagpay').innerHTML;
    shorttagpay2[index].innerHTML = document.getElementById('shorttagpay').innerHTML;
}

const Insert =()=> {
    var table = document.getElementById('inputary');  //表のオブジェクトを取得
 
    var row = table.insertRow(-1);  //行末に行(tr要素)を追加
 
    var cell1 = row.insertCell(0);  //セル(td要素)の追加
    var cell2 = row.insertCell(1);
    var cell3 = row.insertCell(2);
    var cell4 = row.insertCell(3);
    var cell5 = row.insertCell(4);

    var length = table.rows.length-3;

    cell1.innerHTML = "<input type='date' form='input' name='date["+length+"]'  class='shortcutdate'></input>";
    cell2.innerHTML = "<select form='input' name='FromBID["+length+"]' class='shortcuttagpay'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
    cell3.innerHTML = "<select form='input' name='ToBID["+length+"]' class='shortcuttagpay2'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
    cell4.innerHTML = "<input type='number' form='input' name='price["+length+"]' class='shortcutprice'>";
    cell5.innerHTML = "<input type='text' form='input' name='comment["+length+"]' class='shortcutcom'>";
}

/*
const inputary = document.getElementsByClassName('inputary');
const Insert =()=> {
    document.getElementById('inputary').innerHTML=document.getElementById('inputary').innerHTML+"<tr id='"+inputary.length+"' class='inputary'><td><input type='date' form='input' name='date["+inputary.length+"]'  class='shortcutdate'></td><td><select form='input' name='FromBID["+inputary.length+"]' class='shortcuttagpay'></select></td><td><input type='number' min='1' max='"+max_payid+"' form='input' class='shortcutcon' onchange='ShortcutShop_num("+inputary.length+")'><select name='ConID["+inputary.length+"]' class='shortcuttagshop' onchange='ShortcutShop_tag("+inputary.length+")'></select><td><input type='number' form='input' name='price["+inputary.length+"]' class='shortcutprice'></td><td><input type='text' form='input' name='comment["+inputary.length+"]' class='shortcutcom'></td></tr>"
    for (let index = 0; index < shorttagpay.length; index++) {
        shorttagpay[index].innerHTML = document.getElementById('shorttagpay').innerHTML;
        shorttagshop[index].innerHTML = document.getElementById('shorttagshop').innerHTML;
    }
}*/

const ShortcutShop_num =(id)=> {
    no = shortcutcon[id].value;
    document.getElementsByClassName('shortcuttagshop')[id].options[no].setAttribute("selected", "selected");
}

const ShortcutShop_tag =(id)=> {
    shortcutcon[id].value = document.getElementsByClassName('shortcuttagshop')[id].value-10000;
}

const ListShortSet =()=> {
    document.getElementById('test4').innerText="aaa";

    if (listshort_date.value!=null || listshort_date.value!='') {
        for (let index = 0; index < shortcutdate.length; index++) {
            shortcutdate[index].value=listshort_date.value;
        }
    } 

    if (listshort_bank.value!=0) {
        for (let index = 0; index < shorttagpay.length; index++) {
            shorttagpay[index].options[listshort_bank.value-100].setAttribute("selected", "selected");
        }
    } 

    if (listshort_bank2.value!=0) {
        for (let index = 0; index < shorttagpay2.length; index++) {
            shorttagpay2[index].options[listshort_bank2.value-100].setAttribute("selected", "selected");
        }
    }  

    if (listshort_price.value!=null || listshort_price.value!='') {
        for (let index = 0; index < shortcutprice.length; index++) {
            shortcutprice[index].value=listshort_price.value;
        }
    } 

    if (listshort_com.value!=null || listshort_com.value!='') {
        for (let index = 0; index < shortcutcom.length; index++) {
            shortcutcom[index].value=listshort_com.value;
        }
    } 
}

const ListShortClear =()=>{
    listshort_date.value='';
    listshort_bank.options[0].setAttribute("selected", "selected");
    listshort_bank2.options[0].setAttribute("selected", "selected");
    listshort_price.value='';
    listshort_com.value='';
}

const ListShortDataClear =()=>{
    for (let index = 0; index < shortcutdate.length; index++) {
        shortcutdate[index].value = '';
        shorttagpay[index].options[0].setAttribute("selected", "selected");
        shorttagpay2[index].options[0].setAttribute("selected", "selected");
        shortcutprice[index].value = '';
        shortcutcom[index].value = '';
    }
}

const ListShortAllClear =()=>{
    ListShortClear();
    ListShortDataClear();
}

const InsertRecordMovement =(id)=>{
    document.getElementById('test5').innerText="bbb";
    var tablesc = document.getElementById('sc'+id);
    var data="";
    if(tablesc.cells[1].innerText==null || tablesc.cells[1].innerText==''){
        tablesc.cells[1].innerHTML="<input type='date' name='date' form='scform'>";
    } else {
        data = data + "<input type='hidden' name='date' form='scform' value='"+tablesc.cells[1].innerText+"'>"
    }

    if(tablesc.cells[2].innerText==null || tablesc.cells[2].innerText==''){
        tablesc.cells[2].innerHTML="<select name='FromBID'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
    } else {
        data = data + "<input type='hidden' name='FromBID' form='scform' value='"+tablesc.cells[2].id+"'>"
    }

    if(tablesc.cells[3].innerText==null || tablesc.cells[3].innerText==''){
        tablesc.cells[3].innerHTML="<select name='ToBID'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
    } else {
        data = data + "<input type='hidden' name='ToBID' form='scform' value='"+tablesc.cells[3].id+"'>"
    }

    if(tablesc.cells[4].innerText==null || tablesc.cells[4].innerText==''){
        tablesc.cells[4].innerHTML="<input type='number' name='price' form='scform'>";
    } else {
        data = data + "<input type='hidden' name='price' form='scform' value='"+tablesc.cells[4].innerText+"'>"
    }

    if(tablesc.cells[5].innerText==null || tablesc.cells[5].innerText==''){
        tablesc.cells[5].innerHTML="<input type='text' name='comment' form='scform'>";
    } else {
        data = data + "<input type='hidden' name='comment' form='scform' value='"+tablesc.cells[5].innerText+"'>"
    }

    tablesc.cells[6].innerHTML=data+"<input type='submit' name='input_cash' value='入力' form='scform'>";
}

const InsertRecordMovement_list =(id)=>{
    document.getElementById('test6').innerText=id;
    document.getElementById('tab2').checked=true;
    var tablesc = document.getElementById('sc'+id);
    listshort_date.value=tablesc.cells[1].innerText;
    if(tablesc.cells[2].innerText!=null && tablesc.cells[2].innerText!='') {
        listshort_bank.options[tablesc.cells[2].id-100].setAttribute("selected", "selected");
    }
    if(tablesc.cells[3].innerText!=null && tablesc.cells[3].innerText!='') {
        listshort_bank2.options[tablesc.cells[3].id-100].setAttribute("selected", "selected");
    }
    listshort_price.value=tablesc.cells[4].innerText;
    listshort_com.value=tablesc.cells[5].innerText;
    ListShortSet();

}

//const updtf=true;

const updatesc_movement =(id)=>{
    if(updtf){
        /*var table=document.getElementById('payment_shortcut_set');
        for (let index = 0; index < table.rows.length; index++) {
            table.rows[index].cells[0].style.display="none";
        }*/
        var udrow =document.getElementById('scset'+id);
        var name = udrow.cells[1].innerText;
        var date = udrow.cells[2].innerText;
        var frombid = udrow.cells[3].id;
        var tobid = udrow.cells[4].id;
        if (frombid=="") {
            frombid=100;
        }
        if(tobid=="") {
            tobid=100;
        }
        var price = udrow.cells[5].innerText;
        var comment = udrow.cells[6].innerText;
        document.getElementById('test3').innerText=name+","+date+","+price;
        udrow.cells[1].innerHTML="<input form='updtsc' type='text' name='name' value='" + name +  "'>"
        udrow.cells[2].innerHTML="<input form='updtsc' type='date' name='date' value='" + date +  "'>";
        udrow.cells[3].innerHTML="<select name='FromBID' id='shortset_frombid' form='updtsc'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
        document.getElementById('shortset_frombid').options[frombid-100].setAttribute("selected", "selected");
        udrow.cells[4].innerHTML="<select name='ToBID' id='shortset_tobid' form='updtsc'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
        document.getElementById('shortset_tobid').options[tobid-100].setAttribute("selected", "selected");
        udrow.cells[5].innerHTML="<input form='updtsc' type='number' name='price' value='" + price +  "'>";
        udrow.cells[6].innerHTML="<input form='updtsc' type='varchar(50)' name='comment' value='" + comment +  "'>";
        udrow.cells[7].innerHTML="<form method='post' action='' id='updtsc'><input type='hidden' name='id' value='" + id + "'><input type='submit' name='updtsc' value='更新' style='background-color: blue; color: white;'></form>";
        updtf=false;
    }
}

const updateshortcutmovement =()=>{
    if(updtf){
        var table = document.getElementById('payment_shortcut_set');  //表のオブジェクトを取得
 
        var row = table.insertRow(-1);  //行末に行(tr要素)を追加
 
        row.insertCell(0);  //セル(td要素)の追加
        var cell2 = row.insertCell(1);
        var cell3 = row.insertCell(2);
        var cell4 = row.insertCell(3);
        var cell5 = row.insertCell(4);
        var cell6 = row.insertCell(5);
        var cell7 = row.insertCell(6);
        var cell8 = row.insertCell(7);

        cell2.innerHTML="<input form='insertsc' type='text' name='name'>";
        cell3.innerHTML="<input form='insertsc' type='date' name='date'>";
        cell4.innerHTML="<select name='FromBID' form='insertsc'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
        cell5.innerHTML="<select name='ToBID' form='insertsc'>"+document.getElementById('shorttagpay').innerHTML+"</select>";
        cell6.innerHTML="<input form='insertsc' type='number' name='price'>";
        cell7.innerHTML="<input form='insertsc' type='varchar(50)' name='comment'>";
        cell8.innerHTML="<form method='post' action='' id='insertsc'><input type='submit' name='insertsc' form='insertsc' value='更新' style='background-color: blue; color: white;'></form>"
        updtf=false;
    }
}

const MarkSetMovement =()=> {
    var table=document.getElementById('movement_shortcut_set');
    for (let index = 1; index < table.rows.length; index++) {
        var id=table.rows[index].id.substr( 5, 4 );
        table.rows[index].cells[0].innerHTML="<input type='radio' form='mark' name='mark1' value="+id+">1<input type='radio' form='mark' name='mark2' value="+id+">2<input type='radio' form='mark' name='mark3' value="+id+">3<input type='radio' form='mark' name='mark4' value="+id+">4<input type='radio' form='mark' name='mark5' value="+id+">5<input type='radio' form='mark' name='mark6' value="+id+">6";
    }

    //document.getElementsByName('mark1')[0].checked=true;

    var markup = document.getElementById('markup_movement');

    for (let idx = 1; idx < 7; idx++) {
        var mark = document.getElementsByName('mark'+idx);
        for (let index = 0; index < mark.length; index++) {
            if(mark[index].value==markup.rows[idx].cells[1].id) {
                mark[index].checked=true;
            }
        }
    }

    document.getElementById('markbtn').style.display="none";
    document.getElementById('markset').innerHTML="<form action='' method='post' id='mark'><input type='submit' name='markset' value='お気に入り保存'></form>";

    /*markup.rows[0].insertCell(-1);
    for (let index = 1; index < 7; index++) {
        markup.rows[index].insertCell(-1).innerHTML="<form action='' method='post'><input type='hidden' name='id' value="+index+"><input type='submit' name='markupdlt' value='お気に入り解除'></button>";
        
    }*/
}

const InsertFromCard =(id)=> {
    var tablesc = document.getElementById('sc'+id);
    document.getElementById('inputcash_date').value=tablesc.cells[1].innerText;
    if(tablesc.cells[2].innerText!=null && tablesc.cells[2].innerText!='') {
        document.getElementById('selecttag_pay').options[tablesc.cells[2].id-101].setAttribute("selected", "selected");
    }
    if(tablesc.cells[3].innerText!=null && tablesc.cells[3].innerText!='') {
        document.getElementById('selecttag_pay2').options[tablesc.cells[3].id-101].setAttribute("selected", "selected");
    }
    document.getElementById('inputcash_price').value=tablesc.cells[4].innerText;
    document.getElementById('inputcash_comment').value=tablesc.cells[5].innerText;
    }