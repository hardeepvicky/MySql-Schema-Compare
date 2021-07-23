//Ajax start
$(document).ajaxStart(function(){
    $("body").trigger("sr-loader.show");
});

//Ajax complete
$(document).ajaxComplete(function(){
    $("body").trigger("sr-loader.hide");
    Layout.fixContentHeight();
});

$(document).ajaxError(function( event, jqxhr, settings, thrownError ) 
{
    $("body").trigger("sr-loader.hide");
    
    if (typeof jqxhr.responseText == "string" && jqxhr.responseText.length > 0)
    {
        bootbox.alert(jqxhr.responseText);    
    }
    return;
});

$(document).on("change", ".submit-search-on-change", function ()
{
    var form = $(this).parents("form");                
    if( form[0].checkValidity()) 
    {
        $(this).parents("form").trigger("submit");
    }
    else
    {
        form[0].reportValidity();
    }
});

var crud = {
    mainSelector : '.main-page',
    summarySelector : '.page-summary',
    init : function(selector)
    {
        this.blockSelector = selector;
        var _this = this;
        
        $(_this.blockSelector).on("click", ".ajax-paginator a, a.ajax-summary", function(e)
        {
            e.preventDefault();
            
            var href = $(this).attr("href");
            if (href.indexOf("is_summary") == -1)
            {
                href = href + "/is_summary:1";
            }
            $(_this.blockSelector).find(_this.summarySelector).attr("data-url", href);
            _this.pageSummaryLoad();

            return false;
        });
        
        $(_this.blockSelector).on("click", "a.ajax-delete", function(e)
        {
            e.preventDefault();
            
            var href = $(this).attr("href");

            confirmBeforeAjaxGet("Are you sure to Delete ?", href, function(response)
            {
                _this.pageSummaryLoad();
            });

            return false;
        });
        
        $(_this.blockSelector).on("click", "a.ajax-row-delete", function(e)
        {
            e.preventDefault();
            
            var href = $(this).attr("href");
            var _tr = $(this).closest("tr");
            var _table = $(this).closest("table");

            confirmBeforeAjaxGet("Are you sure to Delete ?", href, function(response)
            {
                _tr.remove();
                if (_table.find("tbody tr").length == 0)
                {
                    $(_this.blockSelector).find("form.ajax-search").trigger("submit");
                }
            });

            return false;
        });
        

        $(_this.blockSelector).on("submit", "form[method='get']", function()
        {
            $(this).find("input").each(function()
            {
                var v = $(this).val().trim();

                if (v)
                {
                    v = encodeURIComponent(v);
                    $(this).val(v);
                }
            });
        });
        
        $(_this.blockSelector).on("submit", "form.ajax-search", function()
        {
            var _me = $(this);
            var base_url = $(this).data("base_url");
            var data = $(this).serializeArray();
            var list = [];
            for(var i in data)
            {
                var ele = data[i];
                if (ele.value.length > 0)
                {
                    list.push(ele.name + ":" + ele.value);
                }
            }

            var href = base_url + "/" + list.join("/");
            
            if (href.indexOf("is_summary") == -1)
            {
                href = href + "/is_summary:1";
            }
            
            $(_this.blockSelector).find(_this.summarySelector).attr("data-url", href);
            _this.afterPageSummaryLoad = function()
            {
                _me.find("input").each(function()
                {
                    var v = $(this).val().trim();

                    if (v)
                    {
                        v = decodeURIComponent(v);
                        $(this).val(v);
                    }
                });
            }
            
            _this.pageSummaryLoad();

            return false;
        });


        window.addEventListener('popstate', function(e) 
        {
            if (e.state) 
            {
                $(_this.blockSelector).find(_this.mainSelector).attr("data-url", e.state.url);
                _this.mainLoad();
            }
        });
        
        $(document).on("click", "a.ajax-main, a.ajax-menu", function(e)
        {
            e.preventDefault();
            
            var href = $(this).attr("href");
            
            $(_this.blockSelector).find(_this.mainSelector).attr("data-url", href);
            
            _this.mainLoad();
            
            return false;
        });
        
        $(this.blockSelector).on("submit", "form.ajax-submit", function()
        {
            var url = $(this).attr("data-redirect_url");
            
            ajaxFormPost($(this), 
            {
                success : function()
                {
                    $(_this.blockSelector).find(_this.mainSelector).attr("data-url", url);
                    _this.mainLoad();
                }
            });
            
            return false;
        });
    },
    pageSummaryLoad : function()
    {
        var _this = this;
        
        var url = $(_this.blockSelector).find(_this.summarySelector).attr("data-url");
        if (!url)
        {
            console.error("Url not found");
            return;
        }

        $(this.blockSelector).find(this.summarySelector).load(url, function()
        {
            var result = _this.afterPageSummaryLoad();
            
            if (result === false)
            {
                return false;
            }
            
            App.initAjax();
            window.history.pushState({url : url}, document.title, url);
        });
    },
    afterPageSummaryLoad : function()
    {
        return true;
    },
    mainLoad : function()
    {
        var _this = this;
        
        var url = $(_this.blockSelector).find(_this.mainSelector).attr("data-url");
        if (!url)
        {
            console.error("Url not found");
            return;
        }

        $(this.blockSelector).find(this.mainSelector).load(url, function()
        {
            var result = _this.afterMainLoad();
            
            if (result === false)
            {
                return false;
            }
            
            App.initAjax();
            window.history.pushState({url : url}, document.title, url);
        });
    },
    afterMainLoad : function()
    {
        return true;
    }
};
