'use strict';

String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.replace(new RegExp(search, 'g'), replacement);
};

String.prototype.containsBetween = function(str, start_cap, end_cap)
{
    var arr = [];

    while (str.length > 0)
    {
        var s_ind = str.indexOf(start_cap);
        var e_ind = str.indexOf(end_cap);
        if (s_ind >= 0 && e_ind >= 0)
        {
            var sub = str.substr(s_ind, (e_ind - s_ind + 1));
            arr.push(sub.replace(start_cap, "").replace(end_cap, ""));
            str = str.replace(sub, "");
        }
        else
        {
            return arr;
        }
    }

    return arr;
};


$.sr = {
    
};

$.sr.error = {
    msg : function(msg)
    {
        $.toast({
            text: msg,
            icon: 'error',
            hideAfter: 5000,
            position: 'mid-center',
        });
    },
    detail : function (title, msg)
    {
        $.toast({
            heading: title,
            text: msg,
            icon: 'error',
            hideAfter: 5000,
            position: 'mid-center',
        });
    }
}

$.sr.niceBytes = function(bytes, i)
{
    var list = ["B", "KB", "MB", "GB", "TB"];

    if (typeof i == "undefined")
    {
        i = 0;
    }

    var temp  = bytes / 1024;

    if (temp > 1024)
    {
        return $.sr.niceBytes(temp, i + 1);
    }

    if (temp < 1)
    {
        return bytes.toFixed(1) + " " + list[i];
    }
    else
    {
        return temp.toFixed(1) + " " + list[i + 1];
    }
};

$.sr.wait = function (time, callback, onStopCallback)
{
    var inverval_wait = setInterval(function()
    {
        callback(time);
        time -= 1;

        if (time < 0)
        {
            clearInterval(inverval_wait);
            onStopCallback();
        }
    }, 1000);

    callback(time);
}

$.sr.image = 
{
    toDataUrl : function (src, outputFormat, callback)
    {
        var img = new Image();
        img.crossOrigin = 'Anonymous';
        img.onload = function() {
            var canvas = document.createElement('CANVAS');
            canvas.height = this.height;
            canvas.width = this.width;

            var ctx = canvas.getContext('2d');
            ctx.webkitImageSmoothingEnabled = true;
            ctx.mozImageSmoothingEnabled = true;
            ctx.imageSmoothingEnabled = true;
            ctx.imageSmoothingQuality = "high";

            ctx.drawImage(this, 0, 0);
            var dataURL = canvas.toDataURL(outputFormat, 1.0);
            callback(src, dataURL);
            
            canvas.remove();
        };
        img.src = src;
        if (img.complete || img.complete === undefined) {
            img.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
            img.src = src;
        }
    }
}

jQuery.fn.extend({
    tagName: function ()
    {
        return this.prop("tagName").toLowerCase();
    },
    applyOnce: function (feature)
    {
        if ($(this).hasClass(feature + "-applied"))
        {
            return false;
        }

        $(this).addClass(feature + "-applied");
        return true;
    },
    hasEvent : function(find_e)
    {
        var events = $._data( $(this)[0], "events");        
        for(var e in events)
        {
            if (find_e == e)
            {
                return true;
            }
        }
        
        return false;
    },    
    getBoolFromData : function(name, default_value)
    {
        var _is = $(this).data(name);
            
        if (typeof _is != "undefined")
        {
            if (typeof _is != "string")
            {
                _is = _is.toString();
            }
            
            _is = _is.toLowerCase().trim();

            if (_is == "true" || _is == "1")
            {
                _is = true;
            }
            else
            {
                _is = false;
            }
        }
        else
        {
            _is = default_value;
        }
        
        return _is;
    },
});