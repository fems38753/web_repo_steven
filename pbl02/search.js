function containsLetters(text, input) {
    let index = 0;
    for (let char of input) {
        index = text.indexOf(char, index);
        if (index === -1) return false;
        index++;
    }
    return true;
}

function searchProducts() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let items = document.querySelectorAll(".kaos-item, .jaket-item, .topi-item");
    items.forEach(item => {
        let text = item.innerText.toLowerCase();
        if (containsLetters(text, input)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}