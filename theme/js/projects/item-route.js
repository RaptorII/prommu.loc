/**
 * Created by Stanislav on 07.09.2018.
 */

/**
 * Created by Stanislav on 07.09.2018.
 */

let IndexRoute = (function () {



    function IndexRoute() {
        this.init();
    }

    IndexRoute.prototype.init = function () {
        let self = this;
        console.log(this);
        console.log(self);
    };

    IndexRoute.prototype.alertPopup = function (text) {
        console.log(text);
    };



    return IndexRoute;
}());

$(document).ready(function () {
    new IndexRoute();

    $( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
});