window.addEventListener('DOMContentLoaded', event => {
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

    document.querySelectorAll('.dropdown').forEach(function (ei) {
        ei.addEventListener("mouseover", function (e) {
            let el = this.querySelector("a[data-bs-toggle]");
            if (el != null) {
                let nel = el.nextElementSibling;
                el.classList.add("show");
                nel.classList.add("show");
            }
        });
        ei.addEventListener("mouseleave", function (e) {
            let el = this.querySelector("a[data-bs-toggle]");
            if (el != null) {
                let nel = el.nextElementSibling;
                el.classList.remove("show");
                nel.classList.remove("show");
            }
        });
    });

    document.querySelectorAll("a.nav-link[href='" + window.location.pathname + "']").forEach(function (ee) {
        document.querySelector("a.nav-link[href='" + window.location.pathname + "']").classList.add('text-white');
        document.querySelector("a.nav-link[href='" + window.location.pathname + "']").focus();
    });
});

// var triggerTabList = [].slice.call(document.querySelectorAll('#noticetab button'));
// triggerTabList.forEach(function (triggerEl) {
//     var tabTrigger = new bootstrap.Tab(triggerEl);

//     triggerEl.addEventListener('click', function (event) {
//         event.preventDefault();
//         tabTrigger.show();
//     })
// })