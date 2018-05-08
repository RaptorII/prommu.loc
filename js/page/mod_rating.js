/**
 * Created by wind on 19.05.15.
 */
// dictionary point
var w_points = null;
var gb_rating = null;
var gb_rating_group = 0;
var prj_id = 0;


// ------------ DATA LAYER -------------
// dictionary point
function getRatingDictionary(prj_id, mode) {
    if (!!uid) {
        link = site_url + "?cmd=GET_POINTS&value=&idx=" + prj_id + "&uid=" + uid + "&callback=?";
        jsonp(link, function (data) {
            this.w_points = data;
            if (mode == 0) {
                showRatingDictionaryFull();
            } else {
                showRatingDictionary(mode);
            }
        });
    }
}

// Save rating
// 0 - promo, 1 - empl
function rat(cmd) {
    if (!!uid) {
        var val = null;
        var rating = null;
        val = sumRating();
        rating = JSON.stringify(mapPoints());
        //rating = rating.replace(/,/g, ".");
        var x = '"prj_id":"' + this.prj_id + '", "rating":"' + val + '", "point":' + rating;
        var xen = encript(x, token);
        link = site_url + "?cmd=" + cmd + "&mode=3&jsonvalue=" + xen + "&uid=" + uid + "&callback=?";
        console.log(x);
        jsonp(link, function (data) {
            updateRatingPanel(val, this.prj_id);
        });
    }
}

function mapPoints() {
    map_p = [];
    for (var i = 0; i < this.w_points.length; i++) {
        if (this.gb_rating_group == w_points[i].grp) {
            var obj = this.w_points[i];
            delete obj.descr;
            map_p.push(obj);
        }
    }
    return map_p;
}


// ------------ CONTROLLER -------------
function setRatingPoint(id, val) {
    for (var i = 0; i < this.w_points.length; i++) {
        if (this.w_points[i].id == id) {
            this.w_points[i].point_val = val;
        }
    }
    showRatingDictionaryFull();
}

// ------------ VIEW LAYER -------------
function showRatingEdit(id) {
    this.prj_id = id;
    getRatingDictionary(id, 0);
}

function showRatingDetail(prj_id, mode) {
    this.prj_id = prj_id;
    getRatingDictionary(prj_id, mode);

}

// --- Edit panel ---
function showRatingDictionaryFull() {
    // view
    var html = [];
    var sm = 0;
    if (isEmpty(this.gb_rating) || isEmpty(this.gb_rating_group)) {
        return false;
    }
    html.push('<table class="table table-hover">');
    for (var i = 0; i < w_points.length; i++) {
        if (this.gb_rating_group == w_points[i].grp) {
            html.push('<tr><td>');
            pp = isNaN(this.w_points[i].point_val) ? 0 : this.w_points[i].point_val;
            sm += parseInt(pp);
            html.push(setColorLabel(pp));
            html.push('</td><td>');
            html.push('<button type="button" class="btn btn-danger btn-xs" onclick="setRatingPoint(', w_points[i].id, ', ', 0 - w_points[i].val, ')">-', w_points[i].val, '</button>');
            html.push('<button type="button" class="btn btn-default btn-xs" onclick="setRatingPoint(', w_points[i].id, ',0)">0</button>');
            html.push('<button type="button" class="btn btn-success btn-xs" onclick="setRatingPoint(', w_points[i].id, ', ', w_points[i].val, ')">+', w_points[i].val, '</button>');
            html.push('</td><td>');
            html.push('<label class="checkbox">', w_points[i].descr, '</label>');
            html.push('</td></tr>');
        }
        //'<input type="checkbox" name="fsize" value="1" >', '
    }
    html.push('</table>');
    html.push('Итого ', setColorLabel(sm));
    //html.push('<input type="hidden" name="rat_idx" id="rat_idx" value="', idx, '"/>');

    $("#list_points").html(html.join(''));
}


function showRatingDictionary(mode) {
    // view
    var html = [];
    var sm = 0;
    if (isEmpty(this.gb_rating) || isEmpty(this.gb_rating_group)) {
        return false;
    }
    html.push('<table class="table table-hover">');
    for (var i = 0; i < w_points.length; i++) {
        if (this.gb_rating_group == w_points[i].grp) {
            if (mode < 0) {
                if (this.w_points[i].point_val < 0) {
                    html.push('<tr><td>');
                    sm += parseInt(this.w_points[i].point_val);
                    html.push(setColorLabel(this.w_points[i].point_val));
                    html.push('</td><td>');
                    html.push('<label class="checkbox">', w_points[i].descr, '</label>');
                    html.push('</td></tr>');
                }

            } else {
                if (this.w_points[i].point_val > 0) {
                    html.push('<tr><td>');
                    sm += parseInt(this.w_points[i].point_val);
                    html.push(setColorLabel(this.w_points[i].point_val));
                    html.push('</td><td>');
                    html.push('<label class="checkbox">', w_points[i].descr, '</label>');
                    html.push('</td></tr>');
                }

            }

        }
    }
    html.push('</table>');
    html.push('Итого ', setColorLabel(sm));

    $("#list_points2").html(html.join(''));
}


function updateRatingPanel() {
    var sp = 0;
    var sm = 0;
    var j = 0;

    for (var i = 0; i < this.w_points.length; i++) {
        if (this.gb_rating_group == w_points[i].grp) {
            j = parseInt(this.w_points[i].point_val);
            if (isNaN(j)) j = 0;
            if (j > 0) {
                sp += j;
            }
            if (j < 0) {
                sm += j;
            }
        }
    }

    $("#tp" + prj_id).html(sp);
    $("#tm" + prj_id).html(sm);

    /*
     $("#t_" + prj_id).html(val);
     cl = $("#t_" + prj_id).attr("class");
     $("#t_" + prj_id).removeClass(cl);
     cl = (val > 0) ? "rat_plus" : "rat_minus";
     $("#t_" + prj_id).addClass(cl);
     */
}


// ------------ UTILS FORMS -------------
function setColorLabel(val) {
    var col;
    col = (val > 0) ? 'success' : 'warning';
    col = (val == 0) ? 'default' : col;
    return '<span class="label label-' + col + '">' + val + '</span>';
}

function sumRating() {
    var sm = 0;
    var j = 0;
    for (var i = 0; i < this.w_points.length; i++) {
        j = parseInt(this.w_points[i].point_val);
        if (isNaN(j)) j = 0;
        sm += j;

    }
    return sm;
}

function emptyPointRating(grp) {
    var p = '[';
    for (var i = 0; i < this.w_points.length; i++) {
        if (grp == w_points[i].grp) {
            p += this.w_points[i].id;
            p += ',0,';
        }
    }
    p = p.substr(0, p.length - 1) + ']';
    return p;
}