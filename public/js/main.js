window.onload = () => { // On met un écouteur d'évènements sur tous les boutons répondre
document.querySelectorAll("[data-reply]").forEach(element => {
element.addEventListener("click", function () {
document.querySelector("#comments_parentid").value = this.dataset.id;
});
});
}

