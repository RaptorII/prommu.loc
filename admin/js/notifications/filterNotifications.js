/**
 *
 */
"use_strict";

document.addEventListener('DOMContentLoaded', function(){

    var el = document.querySelector('input[name="user_all"]');
    var itemU = document.querySelectorAll('input[name="user_status[]"]');
    var itemM = document.querySelectorAll('input[name="user_moder[]"]');
    var itemS = document.querySelectorAll('input[name="user_subscribe"]');

    if(el) {
        el.addEventListener("click", function() {
            if (el.checked==true) {
                for (var i = 0; i < itemU.length; i++) {
                    itemU[i].checked = true;
                }
                for (var i = 0; i < itemM.length; i++) {
                    itemM[i].checked = true;
                }
                itemS[0].checked = true;
            }
            if (el.checked==false)  {
                for (var i = 0; i < itemU.length; i++) {
                    itemU[i].checked = false;
                }
                for (var i = 0; i < itemM.length; i++) {
                    itemM[i].checked = false;
                }
                itemS[0].checked = false;
            }
        });

    }

    var elP = document.querySelector('#filter__content-all');
    var itemP = document.querySelectorAll('.filter__content-input');

    // console.log(itemP);

    if(elP) {
        elP.addEventListener("click", function() {
            if (elP.checked==true) {
                for (var i = 0; i < itemP.length; i++) {
                    itemP[i].checked = true;
                }
            }
            if (elP.checked==false)  {
                for (var i = 0; i < itemP.length; i++) {
                    itemP[i].checked = false;
                }
            }
        });
    }

    var elCT = document.querySelector('#cotype-all');
    var itemCT = document.querySelectorAll('.cotype__filter-input');

    console.log(itemCT);

    if(elCT) {
        elCT.addEventListener("click", function() {
            if (elCT.checked==true) {
                for (var i = 0; i < itemCT.length; i++) {
                    itemCT[i].checked = true;
                }
            }
            if (elCT.checked==false)  {
                for (var i = 0; i < itemCT.length; i++) {
                    itemCT[i].checked = false;
                }
            }
        });
    }

});

