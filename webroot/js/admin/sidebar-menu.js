$(document).ready(function () {
//    $('li.top-menu').click(function() {
//
//        $(this).toggleClass("active");
//        $(this).next().slideToggle('300');
//
//    });

    if (Hurad.params.action == "admin_index" || Hurad.params.action == "admin_filter" || Hurad.params.action == "admin_listBycategory" || Hurad.params.action == "admin_listByauthor") {
        //$('a[href$="' + Hurad.params.controller + '"]').parent().parent().parent().parent().addClass("in");//add .in class to div.accordion-body
        $('a[href$="' + Hurad.params.controller + '"]').addClass("active");
    }
    else if (Hurad.params.action == "admin_add") {
        if (Hurad.params.controller == "linkcats" || Hurad.params.controller == "categories" || Hurad.params.controller == "tags") {
            $('a[href$="' + Hurad.params.controller + '"]').addClass("active");
        }
        $('a[href$="' + Hurad.params.controller + '/add"]').addClass("active");
    }
    else if (Hurad.params.action == "admin_edit") {
        if (Hurad.params.controller == "posts" || Hurad.params.controller == "categories" || Hurad.params.controller == "tags") {
            $('a[href$="' + Hurad.params.controller + '"]').addClass("active");
        }
    }
    else if (Hurad.params.action == "admin_prefix") {
        $('a[href$="' + Hurad.params.pass + '"]').addClass("active");
    }
    else if (Hurad.params.action == "admin_dashboard") {
        $('a[href$="admin"]').addClass("active");
    }

    $('a.active').parent('.list-group').parent().parent().addClass("in");
});