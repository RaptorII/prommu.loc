/**
 *
 */
"use_strict";

document.addEventListener('DOMContentLoaded', function(){

    var jobEmployer = document.querySelector('.job_employer');
    var jobSeeker = document.querySelector('.job_seeker');
    var filterPosition = document.querySelector('.filter__position');
    var filterTpempl = document.querySelector('.filter__tpempl');

    if(jobEmployer || jobSeeker) {

        if (jobEmployer.checked==false){
            filterTpempl.style.display = "none";
        }
        if (jobSeeker.checked==false){
            console.log('none2');
            filterPosition.style.display = "none";
        }

        jobEmployer.addEventListener("click", function () {
            if (jobEmployer.checked==true){
                filterTpempl.style.display = '';
            }
            if (jobEmployer.checked==false) {
                filterTpempl.style.display = 'none';
            }

        });

        jobSeeker.addEventListener("click", function () {
            if (jobSeeker.checked==true){
                filterPosition.style.display = '';
            }
            if (jobSeeker.checked==false) {
                filterPosition.style.display = 'none';
            }
        });
    }


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

