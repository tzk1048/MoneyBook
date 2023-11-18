document.getElementById('test3').innerText="aaa";
const inputary = document.getElementsByClassName('inputary');

const Insert =()=> {
    document.getElementById('test4').innerText=inputary.length;
    document.getElementById('inputary').innerHTML=document.getElementById('inputary').innerHTML+"<tr id='"+inputary.length+"' class='inputary'><td><input type='date' form='input' name='date["+inputary.length+"]'></td><td><input type='number' form='input' name='FromBID["+inputary.length+"]'></td><td><input type='number' form='input' name='ConID["+inputary.length+"]'><input type='text' form='input'></td><td><input type='number' form='input' name='price["+inputary.length+"]'></td><td><input type='text' form='input' name='comment["+inputary.length+"]'></td></tr>"
}