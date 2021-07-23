 /* 
 * @author     Hardeep
 */
jQuery.fn.extend({
    checkboxCssToggler : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("checkboxCssToggler-applied"))
            {
                return true;
            }
            
            $(this).addClass("checkboxCssToggler-applied");
            
            $(this).change(function()
            {
                var obj = $($(this).data("toggler-target"));
                var css = $(this).data("toggler-class");
                if (this.checked)
                {
                    obj.addClass(css);
                }
                else
                {
                    obj.removeClass(css);
                }
            });
            
            $(this).trigger("change");
        });
    },
    copyValue : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("copy-applied"))
            {
                return true;
            }
            
            $(this).addClass("copy-applied");
            
            $(this).click(function()
            {                
                var obj = $($(this).data("copy-target"));
                $(obj).select();
                document.execCommand("copy");
            });
        });
    },
    toggleTinyField : function()
    {
        return this.each(function()
        {
            if ($(this).hasClass("toggleTinyField-applied"))
            {
                return true;
            }
            
            $(this).addClass("toggleTinyField-applied");
            
            $(this).click(function()
            {
                var _this = $(this);
                var href = $(this).attr("href");
                var field = $(this).attr("data-field");
                var value = $(this).attr("data-value");
                
                if (!href)
                {
                    console.error("href not found");
                    return;
                }
                
                if (!field)
                {
                    console.error("data-field not found");
                    return;
                }
                
                if (!value)
                {
                    console.error("data-value not found");
                    return;
                }

                var request = {};
                request[field] = value;

                $.post(href, request, function(data)
                {
                    try
                    {
                        data = JSON.parse(data);
                    }
                    catch(e)
                    {
                        bootbox.alert(data);
                        return;
                    }

                    if (data["status"] == "1")
                    {
                        _this.attr("data-value", data[field]);
                        if (data[field] == "1")
                        {
                            _this.html('<i class="fa fa-check-circle-o font-green-meadow icon"></i>');
                        }
                        else
                        {
                            _this.html('<i class="fa fa-times-circle-o font-red-sunglo icon"></i>')
                        }
                    }
                    else
                    {
                        var msg = typeof data["msg"] != "undefined" ? data["msg"] : "Could not change status";
                        bootbox.alert(msg);
                    }
                });

                return false;
            });         
        });
    },
});