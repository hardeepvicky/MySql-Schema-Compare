/**
 * @author     Hardeep
 */
$(document).ready(function()
{
    $("form").find("div.error-message").parents(".form-group").addClass("has-error"); 
   
    $("input[type='checkbox'].chk-select-all").chkSelectAll();

    $(".copy-text").copyText();
    
    $(".css-toggler").cssClassToggle();
    
    $(".checkbox-css-toggler").checkboxCssToggler();
    
    $(".ajax-load").ajaxLoad();
    
    $(".fancybox").fancybox();
    
    $(".fancybox-ajax").fancybox({
        type            : 'ajax',
        autoSize	: false,
    });

    $(".date-picker").datepickerExtend();
    
    $(".date-month-picker").datepickerExtend({
        format: "M-yyyy",
        viewMode: "months", 
        minViewMode: "months"
    });
    
    $(".invalid-char").invalidURLChar();
    
    $("input[type='text'].invalid-sql-char, input[type='number'].invalid-sql-char, textarea.invalid-sql-char").invalidSqlChar();
    
    $("a.toggle-tinyfield").toggleTinyField();
    
    $("select.cascade").cascade();
    
    if (typeof onEventBindFinish == "function")
    {
        onEventBindFinish();
    }
});