// particle.min.js hosted on GitHub
// Scroll down for initialisation code

!(function(a) {
    var b =
        ("object" == typeof self && self.self === self && self) ||
        ("object" == typeof global && global.global === global && global);
    "function" == typeof define && define.amd
        ? define(["exports"], function(c) {
            b.ParticleNetwork = a(b, c);
        })
        : "object" == typeof module && module.exports
        ? (module.exports = a(b, {}))
        : (b.ParticleNetwork = a(b, {}));
})(function(a, b) {
    var c = function(a) {
        (this.canvas = a.canvas),
            (this.g = a.g),
            (this.particleColor = a.options.particleColor),
            (this.x = Math.random() * this.canvas.width),
            (this.y = Math.random() * this.canvas.height),
            (this.velocity = {
                x: (Math.random() - 0.5) * a.options.velocity,
                y: (Math.random() - 0.5) * a.options.velocity
            });
    };
    return (
        (c.prototype.update = function() {
            (this.x > this.canvas.width + 20 || this.x < -20) &&
            (this.velocity.x = -this.velocity.x),
            (this.y > this.canvas.height + 20 || this.y < -20) &&
            (this.velocity.y = -this.velocity.y),
                (this.x += this.velocity.x),
                (this.y += this.velocity.y);
        }),
            (c.prototype.h = function() {
                this.g.beginPath(),
                    (this.g.fillStyle = this.particleColor),
                    (this.g.globalAlpha = 0.7),
                    this.g.arc(this.x, this.y, 1.5, 0, 2 * Math.PI),
                    this.g.fill();
            }),
            (b = function(a, b) {
                (this.i = a),
                    (this.i.size = {
                        width: this.i.offsetWidth,
                        height: this.i.offsetHeight
                    }),
                    (b = void 0 !== b ? b : {}),
                    (this.options = {
                        particleColor: void 0 !== b.particleColor ? b.particleColor : "#fff",
                        background: void 0 !== b.background ? b.background : "#1b2735",
                        interactive: void 0 !== b.interactive ? b.interactive : !0,
                        velocity: this.setVelocity(b.speed),
                        density: this.j(b.density)
                    }),
                    this.init();
            }),
            (b.prototype.init = function() {
                if (
                    ((this.k = document.createElement("div")),
                        this.i.appendChild(this.k),
                        this.l(this.k, {
                            position: "absolute",
                            top: 0,
                            left: 0,
                            bottom: 0,
                            right: 0,
                            "z-index": 1
                        }),
                        /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(this.options.background))
                )
                    this.l(this.k, { background: this.options.background });
                else {
                    if (!/\.(gif|jpg|jpeg|tiff|png)$/i.test(this.options.background))
                        return (
                            console.error(
                                "Please specify a valid background image or hexadecimal color"
                            ),
                                !1
                        );
                    this.l(this.k, {
                        background: 'url("' + this.options.background + '") no-repeat center',
                        "background-size": "cover"
                    });
                }
                if (
                    !/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(this.options.particleColor)
                )
                    return (
                        console.error(
                            "Please specify a valid particleColor hexadecimal color"
                        ),
                            !1
                    );
                (this.canvas = document.createElement("canvas")),
                    this.i.appendChild(this.canvas),
                    (this.g = this.canvas.getContext("2d")),
                    (this.canvas.width = this.i.size.width),
                    (this.canvas.height = this.i.size.height),
                    this.l(this.i, { position: "relative" }),
                    this.l(this.canvas, { "z-index": "20", position: "relative" }),
                    window.addEventListener(
                        "resize",
                        function() {
                            return this.i.offsetWidth === this.i.size.width &&
                            this.i.offsetHeight === this.i.size.height
                                ? !1
                                : ((this.canvas.width = this.i.size.width = this.i.offsetWidth),
                                    (this.canvas.height = this.i.size.height = this.i.offsetHeight),
                                    clearTimeout(this.m),
                                    void (this.m = setTimeout(
                                        function() {
                                            this.o = [];
                                            for (
                                                var a = 0;
                                                a <
                                                this.canvas.width *
                                                this.canvas.height /
                                                this.options.density;
                                                a++
                                            )
                                                this.o.push(new c(this));
                                            this.options.interactive && this.o.push(this.p),
                                                requestAnimationFrame(this.update.bind(this));
                                        }.bind(this),
                                        500
                                    )));
                        }.bind(this)
                    ),
                    (this.o = []);
                for (
                    var a = 0;
                    a < this.canvas.width * this.canvas.height / this.options.density;
                    a++
                )
                    this.o.push(new c(this));
                this.options.interactive &&
                ((this.p = new c(this)),
                    (this.p.velocity = { x: 0, y: 0 }),
                    this.o.push(this.p),
                    this.canvas.addEventListener(
                        "mousemove",
                        function(a) {
                            (this.p.x = a.clientX - this.canvas.offsetLeft),
                                (this.p.y = a.clientY - this.canvas.offsetTop);
                        }.bind(this)
                    ),
                    this.canvas.addEventListener(
                        "mouseup",
                        function(a) {
                            (this.p.velocity = {
                                x: (Math.random() - 0.5) * this.options.velocity,
                                y: (Math.random() - 0.5) * this.options.velocity
                            }),
                                (this.p = new c(this)),
                                (this.p.velocity = { x: 0, y: 0 }),
                                this.o.push(this.p);
                        }.bind(this)
                    )),
                    requestAnimationFrame(this.update.bind(this));
            }),
            (b.prototype.update = function() {
                this.g.clearRect(0, 0, this.canvas.width, this.canvas.height),
                    (this.g.globalAlpha = 1);
                for (var a = 0; a < this.o.length; a++) {
                    this.o[a].update(), this.o[a].h();
                    for (var b = this.o.length - 1; b > a; b--) {
                        var c = Math.sqrt(
                            Math.pow(this.o[a].x - this.o[b].x, 2) +
                            Math.pow(this.o[a].y - this.o[b].y, 2)
                        );
                        c > 120 ||
                        (this.g.beginPath(),
                            (this.g.strokeStyle = this.options.particleColor),
                            (this.g.globalAlpha = (120 - c) / 120),
                            (this.g.lineWidth = 0.7),
                            this.g.moveTo(this.o[a].x, this.o[a].y),
                            this.g.lineTo(this.o[b].x, this.o[b].y),
                            this.g.stroke());
                    }
                }
                0 !== this.options.velocity &&
                requestAnimationFrame(this.update.bind(this));
            }),
            (b.prototype.setVelocity = function(a) {
                return "fast" === a ? 1 : "slow" === a ? 0.33 : "none" === a ? 0 : 0.66;
            }),
            (b.prototype.j = function(a) {
                return "high" === a
                    ? 5e3
                    : "low" === a ? 2e4 : isNaN(parseInt(a, 10)) ? 1e4 : a;
            }),
            (b.prototype.l = function(a, b) {
                for (var c in b) a.style[c] = b[c];
            }),
            b
    );
});

// Initialisation
// window.onresize = function(e) {

if (screen.width >= 768) {

    var canvasDiv = document.getElementById("particle-canvas");
    var options = {
        particleColor: "#888",
        // background: "https://raw.githubusercontent.com/JulianLaval/canvas-particle-network/master/img/demo-bg.jpg",
        interactive: true,
        speed: "medium",
        density: "high"
    };
    var particleCanvas = new ParticleNetwork(canvasDiv, options);

    //size particle block

}
// }

$(function() {

    var hParticle = $("#particle-canvas").height() - 50;

    if (screen.width >= 768) {
        $("div.static__outs.grey-bg").height(hParticle);
    }

    $(window).resize(function(){
        if (screen.width >= 768) {
            $("div.static__outs.grey-bg").height(hParticle);
        }
    });
});

//
//
//
$(function(){
    /**
     * parallax
     * */
    //
    /*
  $(window).on('resize scroll',scrollImage);
  scrollImage();

  //
  function scrollImage() {
    var bHeight = $('.parallax').innerHeight(),
      bPos = $('.parallax').offset().top,
      bPosBottom = bPos + bHeight,
      wHeight = $(window).innerHeight(),
      wNewPos = $(window).scrollTop(),
      wPosBottom = wNewPos + wHeight,
      $img = $('.parallax img'),
      hImage = $img.innerHeight();

    if(
      ( (bPos > wNewPos) && (bPos < wPosBottom ) )
      ||
      ( (bPosBottom > wNewPos) && (bPosBottom < wPosBottom ) )
    ) // функционал работает только в поле зрения юзера
    {
        var offset;
        if ((screen.width >= 1680) && (screen.width <= 1900) )  {
            offset = -430 + (bPos - wNewPos)/4;
        } else if (screen.width >= 1900) {
            offset = -470 + (bPos - wNewPos)/4;
        } else {
            offset = -300 + (bPos - wNewPos)/4;
        }
//      console.log(offset);

      if( (bPos + hImage + offset) >= bPosBottom )
      {
        $img.css('top',offset+'px');
      }
      else
      {
        $img.css('top','0');
      }
    }
  }

  /* end parallax */
    //
    function showMessage(string) {
        $('body').append('<div class="prmu__popup"><p>' + string + '</p></div>'),
            $.fancybox.open({
                src: "body>div.prmu__popup",
                type: 'inline',
                touch: false,
                afterClose: function(){ $('body>div.prmu__popup').remove() }
            })
    }
    //
    $('.applicant_create_vac').click(function(){
        showMessage('Нам очень жаль :( но Вы зарегистрированы как соискатель и не можете публиковать вакансии');
    });
    //
    $('.geo_message').click(function(){
        showMessage('<span style="display: block; text-transform: uppercase; text-align: center; font-size: 13px;">Извините, данная услуга еще в разработке.</span> ' +
            '<span style="display: block; text-transform: uppercase; text-align: center; font-size: 13px;">Как только она активизируется - мы</span>' +
            '<span style="display: block; text-transform: uppercase; text-align: center; font-size: 13px;">обязательно оповестим Вас</span>' +
            '<br>' +
            '<span style="display: block; font-size: 12px; text-align: right; font-style: italic">С уважением, Администрация Prommu</span>');
    });
    //
    $('.applicant_service').click(function(){
        showMessage('Нам очень жаль :( но Вы зарегистрированы как соискатель и не можете воспользоваться данной услугой');
    });
    //
    $('.guest__reg').click(function(){
        showMessage('<p class="center"> Уважаемый пользователь! <br> ' +
            'Что бы заказать данную услугу, необходимо </p>' +
            '<a class="btn__orange center text-uppercase" href="/user/register/type">зарегистрироваться</a>' +
            '<hr>'+
            '<p class="center">После регистрации необходимо создать вакансию и пройти модерацию. После этой процедуры все услуги <b>станут доступны</b>.</p>'
        );
    });
    //
    $('.guest__reg-applic').click(function(){
        showMessage('Нам очень жаль :( но Вы зарегистрированы как соискатель и дааная услуга вам недоступна');
    });

});