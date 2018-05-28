require([
    "jquery",
    "jquery/jquery.cookie"
], function ($) {
//<![CDATA[
    $(document).ready(function () {
        function getParameterByName(name, url) {
            if (!url) {
                url = window.location.href;
            }
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        var mpId = getParameterByName("mpid");
        if (mpId) {
            $.cookie("mpId", mpId);
        }
    });
//]]>
});