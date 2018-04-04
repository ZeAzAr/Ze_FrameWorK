(function(a){
    a.fn.webwidget_menu_vertical_menu1=function(p){
        var p=p||{};

        var i=p&&p.font_decoration?p.font_decoration:"none";
        var n=p&&p.animation_speed?p.animation_speed:"fast";
        var o=a(this);
        if(o.children("ul").length==0||o.children("ul").children("li").length==0){
            o.append("Require menu content");
            return null
        }
        init();
        function init(){
            o.children("ul").children("li").children("a").css("text-decoration",i);
            o.children("ul").children("li:has(a)").hover(
                function(){
                    mouseover($(this));
                },
                function(){
                    mouseout($(this));
                }
            );
            o.children("ul").children("li").children("ul").children("li").children("a").css("text-decoration",i);
            o.children("ul").children("li").children("ul").children("li:has(a)").hover(
            );
        }
        function mouseover(dom){
            dom.children("ul").fadeIn(n);
        }
        function mouseout(dom){
            dom.children("ul").fadeOut(n);
        }
    }
})(jQuery);