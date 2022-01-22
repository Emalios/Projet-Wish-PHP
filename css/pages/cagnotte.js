let slider = document.getElementById("myRange"); 
let par = document.getElementById("donation"); 
par.innerHTML = "Donner " + slider.value + " €";
slider.addEventListener('input', function(){
    console.log("erreur " + slider.value);
    par.innerHTML = "Donner " + slider.value + " €";
});