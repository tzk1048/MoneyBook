const dex = document.getElementsByClassName('dex');

for(let elem of dex){
    elem.style.height = elem.getAttribute('id')/100+'px';
}