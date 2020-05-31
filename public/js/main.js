function saveVisitor(uuid, browser){
        $.ajax({
            url: '/save-visitor',
            data: {
                uuid: uuid,
                browser: browser
            },
            type: 'POST',
            success: function ( response ) {
                console.log(response);
            }
        });
    }

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

$(document).ready(function(){
    var agent = 'other';
    var ua = navigator.userAgent.toLowerCase();
    if(s = ua.match(/msie ([\d.]+)/)){
        agent = 'ie';
    }
    if(s = ua.match(/firefox\/([\d.]+)/)){
        agent = 'firefox';
    }
    if(s = ua.match(/chrome\/([\d.]+)/)){
        agent = 'chrome';
    }
    if(s = ua.match(/opera.([\d.]+)/)){
        agent = 'opera';
    }
    if(s = ua.match(/version\/([\d.]+).*safari/)){
        agent = 'safari';
    }

    var uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
    })
    var clientCookie = getCookie('uuid');
    if(clientCookie){
        $.ajax({
            url: '/check-visitor',
            data: {
                uuid: clientCookie
            },
            type: 'POST',
            success: function ( response ) {
                if(!response.exists){
                    saveVisitor(clientCookie, agent);
                }
            }
        });
    } else {
        document.cookie = `uuid=${uuid}; path=/; expires=Tue, 19 Jan 2038 03:14:07 GMT`;
        saveVisitor(uuid, agent);
    }
});
