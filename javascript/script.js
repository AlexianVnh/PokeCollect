// Attendez que le DOM soit chargé avant d'exécuter le script
document.addEventListener("DOMContentLoaded", function () {
    // Sélectionnez le bouton de soumission par son ID
    const submitBtn = document.getElementById("submitBtn");
    
    // Sélectionnez les boutons "top login" et "top signin" par leur classe
    const loginBtn = document.querySelector(".top.login");
    const typeInput = document.querySelector(".login-input#login");

    const signinBtn = document.querySelector(".top.signin");
    const optionalMdp = document.querySelector(".login-content-optional");

    optionalMdp.style.display = "none";

    // Ajoutez un écouteur d'événements au bouton "top login"
    loginBtn.addEventListener("click", function () {
        // Changez la valeur et la couleur de fond du bouton de soumission
        submitBtn.value = "Se connecter";
        
        typeInput.name = "login";
        loginBtn.style.backgroundColor = "var(--darkColor)";
        loginBtn.style.color = "var(--textLightColor)";
        signinBtn.style.backgroundColor = "var(--veryDarkColor)";
        signinBtn.style.color = "var(--textLightColor)";

        optionalMdp.style.display = "none";
    });

    // Ajoutez un écouteur d'événements au bouton "top signin"
    signinBtn.addEventListener("click", function () {
        // Changez la valeur et la couleur de fond du bouton de soumission
        submitBtn.value = "S'inscrire";

        typeInput.name = "register";
        loginBtn.style.backgroundColor = "var(--veryDarkColor)";
        loginBtn.style.color = "var(--textLightColor)";
        signinBtn.style.backgroundColor = "var(--darkColor)";
        signinBtn.style.color = "var(--textLightColor)";

        optionalMdp.style.display = "flex";
        optionalMdp.style.margin = "8px 0 0 0";
    });



    // Sélectionnez les articles pokeball-left et pokeball-right
    const pokeballLeft = document.querySelector('.pokeball-left');
    const pokeballRight = document.querySelector('.pokeball-right');

    // Sélectionnez les contenus des balises pokeball-left et pokeball-right
    const pokeballLeftContent = document.querySelector('.pokeball-left-content');
    const pokeballRightContent = document.querySelector('.pokeball-right-content');

    pokeballLeft.addEventListener("click", function () {
        console.log('pokeballLeft clicked');
        pokeballLeftContent.classList.toggle('open');
    });
    
    pokeballRight.addEventListener("click", function () {
        console.log('pokeballRight clicked');
        pokeballRightContent.classList.toggle('open');
    });





});