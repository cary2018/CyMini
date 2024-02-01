//回复评论
zbp.plugin.on("comment.reply.start", "quietlee", function(a) {
    var f = a;
    $("#inpRevID").val(f);
    var e = $("#comt-respond");
    var X = $("#cancel-reply");
    e.before($("<div id='temp-frm' style='display:none'>")).addClass("reply-frm");
    $("#AjaxComment" + f).before(e);
    e.addClass("");
    X.show().click(function() {
        var a = $("#temp-frm");
        $("#inpRevID").val(0);
        if (!a.length || !e.length)
            return a.before(e);
        a.remove();
        $(this).hide();
        e.removeClass("");
        $(".commentlist").before(e);
        return false
    });
    try {
        $("#txaArticle").focus()
    } catch (a) {}
    return false
});

//开启阅读模式
(function(a) {
        function f(e) {
            e.removeAttr("style").removeAttr("class").removeAttr("id");
            e = e.children();
            null != e && e.each(function() {
                f(a(this))
            })
        }
        a.fn.easyread = function(e) {
            var X = a.extend({
                contentClass: "none",
                titleClass: "none",
                videoClass: "none"
            }, e);
            a(this).bind("click", function() {
                a("body").css({
                    overflow: "hidden"
                });
                a("article").append('<div id="bg_read" style="position:fixed; top: 0px; left: 0px; right: 0px; bottom: 0px; width: 100%; height: 100%; border: none; margin: 0px; padding: 0px; overflow: hidden; z-index: 9998; background-color: white; opacity: 1; "><div style="position:absolute; width:32px; height32px; left:50%; top:50%"><span class="loading_read"></span></div></div>');
                var e = a("." + X.titleClass).text()
                    , b = a("." + X.contentClass).clone();
                b.children().each(function() {
                    f(a(this))
                });
                e = '<div class="box_read"><div class="entry-content"><h1>' + e + "</h1>" + b.html() + '<div style="clear: both;"></div></div></div><span class="close_read"></span>';
                a("#bg_read").append(e);
                a(".title_read").animate({
                    marginTop: "5px"
                }, 288);
                a(".loading_read").parent("div").remove();
                a(".close_read").bind("click", function() {
                    a("#bg_read").fadeOut(288, function() {
                        a(this).remove();
                        a("body").css("overflow", "auto")
                    });
                    return !1
                });
                return !1
            })
        }
    }
)(jQuery);
//阅读模式
$(document).ready(function() {
    $(".read").easyread({
        titleClass: "page-title",
        contentClass: "entry-content",
        videoClass: "entry-video"
    })
});
//快捷回复
function addNumber(a) {
    document.getElementById("txaArticle").value += a
}
//回到顶部
$(function() {
    $("#backtop").each(function() {
        $(this).find(".top").click(function() {
            $("html, body").animate({
                "scroll-top": 0
            }, "fast")
        });
        $(".bottom").click(function() {
            $("html, body").animate({
                scrollTop: $(".footer").offset().top
            }, 800);
            return false
        })
    });
    var a = false;
    $(window).scroll(function() {
        var f = $(window).scrollTop();
        if (f > 500) {
            $("#backtop").data("expanded", true)
        } else {
            $("#backtop").data("expanded", false)
        }
        if ($("#backtop").data("expanded") != a) {
            a = $("#backtop").data("expanded");
            if (a) {
                $("#backtop .top").slideDown()
            } else {
                $("#backtop .top").slideUp()
            }
        }
    })
});

//下拉菜单进度效果
$(window).scroll(function() {
    var a = $(window).scrollTop()
        , f = $(document).height()
        , e = $(window).height();
    scrollPercent = a / (f - e) * 100;
    scrollPercent = scrollPercent.toFixed(1);
    $("#percentageCounter").css({
        width: scrollPercent + "%"
    });
    70 < scrollPercent && $("#navigation").css({
        top: "0"
    });
    70 > scrollPercent && $("#navigation").css({
        top: "-166px"
    })
}).trigger("scroll");
//下拉收起搜索框
(function(a) {
        a.fn.theiaStickySidebar = function(f) {
            var e = {
                containerSelector: "",
                additionalMarginTop: 0,
                additionalMarginBottom: 0,
                updateSidebarHeight: true,
                minWidth: 0,
                disableOnResponsiveLayouts: true,
                sidebarBehavior: "modern"
            };
            f = a.extend(e, f);
            f.additionalMarginTop = parseInt(f.additionalMarginTop) || 0;
            f.additionalMarginBottom = parseInt(f.additionalMarginBottom) || 0;
            X(f, this);
            function X(f, e) {
                var X = b(f, e);
                if (!X) {
                    console.log("TST: Body width smaller than options.minWidth. Init is delayed.");
                    a(document).scroll(function(f, e) {
                        return function(X) {
                            var Z = b(f, e);
                            if (Z) {
                                a(this).unbind(X)
                            }
                        }
                    }(f, e));
                    a(window).resize(function(f, e) {
                        return function(X) {
                            var Z = b(f, e);
                            if (Z) {
                                a(this).unbind(X)
                            }
                        }
                    }(f, e))
                }
            }
            function b(f, e) {
                if (f.initialized === true) {
                    return true
                }
                if (a("body").width() < f.minWidth) {
                    return false
                }
                Z(f, e);
                return true
            }
            function Z(f, e) {
                f.initialized = true;
                a("head").append(a('<style>.theiaStickySidebar:after {content: ""; display: table; clear: both;}</style>'));
                e.each(function() {
                    var e = {};
                    e.sidebar = a(this);
                    e.options = f || {};
                    e.container = a(e.options.containerSelector);
                    if (e.container.length == 0) {
                        e.container = e.sidebar.parent()
                    }
                    e.sidebar.parents().css("-webkit-transform", "none");
                    e.sidebar.css({
                        position: "relative",
                        overflow: "visible",
                        "-webkit-box-sizing": "border-box",
                        "-moz-box-sizing": "border-box",
                        "box-sizing": "border-box"
                    });
                    e.stickySidebar = e.sidebar.find(".theiaStickySidebar");
                    if (e.stickySidebar.length == 0) {
                        var X = /(?:text|application)\/(?:x-)?(?:javascript|ecmascript)/i;
                        e.sidebar.find("script").filter(function(a, f) {
                            return f.type.length === 0 || f.type.match(X)
                        }).remove();
                        e.stickySidebar = a("<div>").addClass("theiaStickySidebar").append(e.sidebar.children());
                        e.sidebar.append(e.stickySidebar)
                    }
                    e.marginTop = parseInt(e.sidebar.css("margin-top"));
                    e.marginBottom = parseInt(e.sidebar.css("margin-bottom"));
                    e.paddingTop = parseInt(e.sidebar.css("padding-top"));
                    e.paddingBottom = parseInt(e.sidebar.css("padding-bottom"));
                    var b = e.stickySidebar.offset().top;
                    var Z = e.stickySidebar.outerHeight();
                    e.stickySidebar.css("padding-top", 0);
                    e.stickySidebar.css("padding-bottom", 0);
                    b -= e.stickySidebar.offset().top;
                    Z = e.stickySidebar.outerHeight() - Z - b;
                    if (b == 0) {
                        e.stickySidebar.css("padding-top", 0);
                        e.stickySidebarPaddingTop = 0
                    } else {
                        e.stickySidebarPaddingTop = 0
                    }
                    if (Z == 0) {
                        e.stickySidebar.css("padding-bottom", 0);
                        e.stickySidebarPaddingBottom = 0
                    } else {
                        e.stickySidebarPaddingBottom = 0
                    }
                    e.previousScrollTop = null;
                    e.fixedScrollTop = 0;
                    g();
                    e.onScroll = function(e) {
                        if (!e.stickySidebar.is(":visible")) {
                            return
                        }
                        if (a("body").width() < e.options.minWidth) {
                            g();
                            return
                        }
                        if (e.options.disableOnResponsiveLayouts) {
                            var X = e.sidebar.outerWidth(e.sidebar.css("float") == "none");
                            if (X + 50 > e.container.width()) {
                                g();
                                return
                            }
                        }
                        var b = a(document).scrollTop();
                        var Z = "static";
                        if (b >= e.container.offset().top + (e.paddingTop + e.marginTop - e.options.additionalMarginTop)) {
                            var c = e.paddingTop + e.marginTop + f.additionalMarginTop;
                            var O = e.paddingBottom + e.marginBottom + f.additionalMarginBottom;
                            var fZ = e.container.offset().top;
                            var d = e.container.offset().top + P(e.container);
                            var J = 0 + f.additionalMarginTop;
                            var eY;
                            var ci = e.stickySidebar.outerHeight() + c + O < a(window).height();
                            if (ci) {
                                eY = J + e.stickySidebar.outerHeight()
                            } else {
                                eY = a(window).height() - e.marginBottom - e.paddingBottom - f.additionalMarginBottom
                            }
                            var aP = fZ - b + e.paddingTop + e.marginTop;
                            var cM = d - b - e.paddingBottom - e.marginBottom;
                            var gK = e.stickySidebar.offset().top - b;
                            var gV = e.previousScrollTop - b;
                            if (e.stickySidebar.css("position") == "fixed") {
                                if (e.options.sidebarBehavior == "modern") {
                                    gK += gV
                                }
                            }
                            if (e.options.sidebarBehavior == "stick-to-top") {
                                gK = f.additionalMarginTop
                            }
                            if (e.options.sidebarBehavior == "stick-to-bottom") {
                                gK = eY - e.stickySidebar.outerHeight()
                            }
                            if (gV > 0) {
                                gK = Math.min(gK, J)
                            } else {
                                gK = Math.max(gK, eY - e.stickySidebar.outerHeight())
                            }
                            gK = Math.max(gK, aP);
                            gK = Math.min(gK, cM - e.stickySidebar.outerHeight());
                            var ge = e.container.height() == e.stickySidebar.outerHeight();
                            if (!ge && gK == J) {
                                Z = "fixed"
                            } else if (!ge && gK == eY - e.stickySidebar.outerHeight()) {
                                Z = "fixed"
                            } else if (b + gK - e.sidebar.offset().top - e.paddingTop <= f.additionalMarginTop) {
                                Z = "static"
                            } else {
                                Z = "absolute"
                            }
                        }
                        if (Z == "fixed") {
                            e.stickySidebar.css({
                                position: "fixed",
                                width: e.sidebar.width(),
                                top: gK,
                                left: e.sidebar.offset().left + parseInt(e.sidebar.css("padding-left"))
                            })
                        } else if (Z == "absolute") {
                            var aO = {};
                            if (e.stickySidebar.css("position") != "absolute") {
                                aO.position = "absolute";
                                aO.top = b + gK - e.sidebar.offset().top - e.stickySidebarPaddingTop - e.stickySidebarPaddingBottom
                            }
                            aO.width = e.sidebar.width();
                            aO.left = "";
                            e.stickySidebar.css(aO)
                        } else if (Z == "static") {
                            g()
                        }
                        if (Z != "static") {
                            if (e.options.updateSidebarHeight == true) {
                                e.sidebar.css({
                                    "min-height": e.stickySidebar.outerHeight() + e.stickySidebar.offset().top - e.sidebar.offset().top + e.paddingBottom
                                })
                            }
                        }
                        e.previousScrollTop = b
                    }
                    ;
                    e.onScroll(e);
                    a(document).scroll(function(a) {
                        return function() {
                            a.onScroll(a)
                        }
                    }(e));
                    a(window).resize(function(a) {
                        return function() {
                            a.stickySidebar.css({
                                position: "static"
                            });
                            a.onScroll(a)
                        }
                    }(e));
                    function g() {
                        e.fixedScrollTop = 0;
                        e.sidebar.css({
                            "min-height": "0px"
                        });
                        e.stickySidebar.css({
                            position: "static",
                            width: ""
                        })
                    }
                    function P(f) {
                        var e = f.height();
                        f.children().each(function() {
                            e = Math.max(e, a(this).height())
                        });
                        return e
                    }
                })
            }
        }
    }
)(jQuery);
//右侧粘框
$(document).ready(function() {
    $(".site-main .side").theiaStickySidebar({
        additionalMarginTop: 15,
        additionalMarginBottom: 15
    })
});
//下拉收起搜索框
$(function() {
    var a = $(".top-bar");
    var f = $(document).scrollTop();
    var e = $(document);
    var X = $(".fixed-nav").outerHeight();
    $(window).scroll(function() {
        var b = $(document).scrollTop();
        if (e.scrollTop() >= 0) {
            a.addClass("fixed-nav");
            $(".navTmp").fadeIn()
        } else {
            a.removeClass("fixed-nav fixed-enabled fixed-appear");
            $(".navTmp").fadeOut()
        }
        if (b > X) {
            $(".fixed-nav").addClass("fixed-enabled")
        } else {
            $(".fixed-nav").removeClass("fixed-enabled")
        }
        if (b > f) {
            $(".fixed-nav").removeClass("fixed-appear")
        } else {
            $(".fixed-nav").addClass("fixed-appear")
        }
        f = $(document).scrollTop()
    })
});
//pc点击菜单
$(function() {
    var a = $(".place a:eq(1)").attr("href");
    var f = location.href;
    var e = document.location;
    $(".nav-pills li a").each(function() {
        if ($(this).attr("href") == a || $(this).attr("href") == f) {
            $(this).parent("li").addClass("active")
        }
        if (this.href == e.toString().split("#")[0]) {
            $(this).parent("li").addClass("active");
            $(this).parent().parent().parent("li").addClass("active");
            return false
        }
    })
});

//日夜模式开关
function switchNightMode() {
    if (zbp.cookie.get("night") == "1" || $("body").hasClass("night")) {
        zbp.cookie.set("night", "0");
        $("body").removeClass("night");
        //console.log("夜间模式关闭")
    } else {
        zbp.cookie.set("night", "1");
        $("body").addClass("night");
        //console.log("夜间模式开启")
    }
}
//手机端菜单收起/展开
jQuery(document).ready(function() {
    $("<span class='toggle-btn'><i class='fa fa-chevron-down'></i></span>").insertBefore(".sub-menu");
    $("#list1,#list2,#list3,.widget:nth-child(1),.widget:nth-child(2)").removeClass("wow");
    $("#list1,#list2,#list3,.widget:nth-child(1),.widget:nth-child(2)").removeClass("fadeInDown");
    var a = $(".nav-sousuo");
    $("#m-nav-so i").click(function() {
        $(".mini-search").slideToggle();
        $("input.searchInput").focus();
        $("body.home").toggleClass("cur")
    });
    $(".m_nav-list i").click(function() {
        $(".m_nav-list").toggleClass("m_nav-close");
        $("body.home").toggleClass("cur");
        $(".sub-menu").toggleClass("m-sub-menu");
        $(".mobile_nav").toggleClass("mobile_nav_on")
    });
    $(".menu-item").click(function() {
        $(".menu-item").removeClass("active");
        $(this).addClass("active")
    });
    $(".zanter,.rewards-popover-close i").click(function() {
        $(this).removeClass("primary");
        $(".rewards-popover-mask,.rewards-popover").toggleClass("primary")
    });
    jQuery(".mobile-menu .nav-pills > li,.mobile-menu .nav-pills > li ul li").each(function() {
        jQuery(this).children(".mobile-menu .m-sub-menu").not(".active").css("display", "none");
        jQuery(this).children(".mobile-menu .toggle-btn").bind("click", function() {
            $(".mobile-menu .m-sub-menu").addClass("active");
            jQuery(this).children().addClass(function() {
                if (jQuery(this).hasClass("active")) {
                    jQuery(this).removeClass("active");
                    return ""
                }
                return "active"
            });
            jQuery(this).siblings(".mobile-menu .m-sub-menu").slideToggle()
        })
    })
});
//文章内容文字大小缩放
jQuery(document).ready(function(a) {
    a("#font-change span").click(function() {
        var f = ".entry-content p";
        var e = 1;
        var X = 15;
        var b = a(f).css("fontSize");
        var Z = parseFloat(b, 10);
        var g = b.slice(-2);
        var P = a(this).attr("id");
        switch (P) {
            case "font-dec":
                Z -= e;
                break;
            case "font-inc":
                Z += e;
                break;
            default:
                Z = X
        }
        a(f).css("fontSize", Z + g);
        return false
    })
});