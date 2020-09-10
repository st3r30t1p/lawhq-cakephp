function reload() {
    let matId = document.getElementById("matter-id").value;
    let e = document.getElementById("selectTemplate");
    let currentURL = window.location.pathname + window.location.hash;

    window.location.href = currentURL + '?mat_id=' + matId + '&template=' + e.options[e.selectedIndex].value;
}
