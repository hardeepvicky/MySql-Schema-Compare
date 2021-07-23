jQuery.fn.extend({
    srDatatable: function ()
    {
        var feature = "sr-data-table";

        return this.each(function ()
        {
            var _table = $(this);

            if (_table.applyOnce(feature))
            {
                _table.find("> thead > tr > th").each(function (index, value)
                {
                    var _th = $(this);
                    var will_clear = _th.getBoolFromData(feature + "-search-clear", false);
                    var will_search = _th.getBoolFromData(feature + "-search", false);
                    var will_info = _th.getBoolFromData(feature + "-info", false);
                    var sort_type = _th.data(feature + "-sort");
                    
                    var html = _th.html();
                    var html_change = false;
                    if (typeof sort_type != "undefined" && sort_type)
                    {
                        html += "<span class='sr-data-table-sort'><i class='fa fa-sort'></i></span>";

                        if (sort_type == "numeric")
                        {
                            if (will_info)
                            {
                                html += '<a href="#" class="info-tooltip" data-placement="bottom" title="Hooray!"><i class="fa fa-info-circle"></i></a>';
                            }
                        }
                        html_change = true;
                    }

                    if (will_search)
                    {
                        html += "<span class='search-icon'><i class='fa fa-search'></i></span><div class='search-block'><input type='text' data-col_index='" + index + "' /></div>";
                        html_change = true;
                    }
                    else if (will_clear)
                    {
                        html += "<span class='search-clear-icon'><i class='fa fa-remove'></i></span>";
                        html_change = true;
                    }

                    if (html_change)
                    {
                        _th.html(html);
                    }
                    
                    if (typeof sort_type != "undefined" && sort_type == "numeric")
                    {
                        _th.find(".info-tooltip").tooltip();
                        _th.find(".info-tooltip").on('show.bs.tooltip', function () {
                            var _th = $(this).closest("th");
                            var index = _th.index();

                            var sum, count;
                            sum = count = 0;
                            _table.find("> tbody > tr").not(".hidden").each(function ()
                            {
                                var _td = $(this).find(">td:eq(" + index + ")");

                                var v = parseFloat(_td.text());
                                if ($.isNumeric(v))
                                {
                                    sum += parseFloat(_td.text());
                                }
                                count++;
                            });

                            _th.find(".info-tooltip").attr("data-original-title", "Sum : " + sum.toFixed(3) + ", Count : " + count);
                        });
                    }

                    if (will_search)
                    {
                        _th.find(".search-icon").click(function ()
                        {
                            var _th = $(this).closest("th");
                            var index = _th.index();

                            var search = _th.find(".search-block input").val().trim().toLowerCase();
                            var hide_counter = 0;
                            _table.find("> tbody > tr").each(function ()
                            {
                                var _td = $(this).find(">td:eq(" + index + ")");
                                if (search)
                                {
                                    var v = _td.text().toLowerCase();

                                    if (v.indexOf(search) < 0)
                                    {
                                        _td.addClass("sr-hide");
                                        hide_counter++;
                                    } 
                                    else
                                    {
                                        _td.removeClass("sr-hide");
                                    }
                                }
                                else
                                {
                                    _td.removeClass("sr-hide");
                                }
                            });


                            if (hide_counter > 0)
                            {
                                _th.find(".search-icon").addClass("active");
                            } 
                            else
                            {
                                _th.find(".search-icon").removeClass("active");
                            }

                            _table.find(">tbody > tr").each(function(t, tr)
                            {
                                if ( $(tr).find("> td.sr-hide").length > 0)
                                {
                                    $(tr).addClass("hidden");
                                }
                                else
                                {
                                    $(tr).removeClass("hidden");
                                }
                            });                            
                        });

                        _th.find(".search-block input").keyup(function (e)
                        {
                            var old_val = $(this).data("old-val");
                            var searching = _th.getBoolFromData("searching", false);
                            
                            if (!searching && $(this).val() != old_val)
                            {
                                _th.data("searching", true);
                                $(this).data("old-val", $(this).val());
                                
                                setTimeout(function () 
                                {
                                    console.log("searching..");
                                    
                                    _th.find(".search-icon").trigger("click");
                                    _th.data("searching", false);
                                }, 300);
                            }
                        });
                    }

                    if (will_clear)
                    {
                        _th.find(".search-clear-icon").click(function ()
                        {
                            _table.children("thead").find(".search-block").find("input").val("");
                            _table.children("thead").find(".search-icon").removeClass("active");

                            _table.find(">tbody > tr > td").removeClass("sr-hide");
                            _table.find(">tbody > tr").removeClass("hidden");
                        });
                    }

                    if (typeof sort_type != "undefined" && sort_type)
                    {
                        _th.find(".sr-data-table-sort").click(function ()
                        {
                            var _th = $(this).closest("th");
                            var index = _th.index();

                            var sort_type = _th.attr("data-sort");
                            var sort_dir = "ASC";

                            if ($(this).find(".fa-sort-asc").length > 0)
                            {
                                sort_dir = "DESC";
                            }

                            var list = [];

                            _table.find("> tbody > tr").not(".hidden").each(function (row_index, tr)
                            {
                                var _td = $(tr).find(">td:eq(" + index + ")");
                                var text = _td.text().trim();
                                if (sort_type == "numeric")
                                {
                                    text = parseFloat(text);
                                }
                                list.push({row: row_index, text: text});
                            });

                            list.sort(function(a, b)
                            {
                                if (sort_dir == "ASC")
                                {
                                    if (a.text < b.text)
                                    {
                                        return -1;
                                    }
                                    if (a.text > b.text)
                                    {
                                        return 1;
                                    }
                                }
                                else
                                {
                                    if (a.text < b.text)
                                    {
                                        return 1;
                                    }
                                    if (a.text > b.text)
                                    {
                                        return -1;
                                    }
                                }

                                return 0;
                            });

                            var trs = _table.find(">tbody > tr");

                            _table.find(">tbody").html("");

                            console.log(list);

                            for(var i in list)
                            {
                                var obj = list[i];
                                _table.find(">tbody").append(trs[obj.row]);
                            }
                            
                            if (sort_dir == "ASC")
                            {
                                $(this).html('<i class="fa fa-sort-asc"></i>');
                            }
                            else
                            {
                                $(this).html('<i class="fa fa-sort-desc"></i>');
                            }
                        });
                    }
                });
            }
        });
    },
});