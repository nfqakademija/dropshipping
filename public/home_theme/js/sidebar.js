(function() {
    'use strict';

    document.getElementById('sidebar-collapse-btn').onclick = function () {
        let element = document.getElementById("app");
        element.classList.add("sidebar-open");
    };

    document.getElementById('sidebar-overlay').onclick = function () {
        let element = document.getElementById("app");
        element.classList.remove("sidebar-open");
    };

}());
