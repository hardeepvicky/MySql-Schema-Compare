jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');
    this.doc.setFontSize(6.3);                

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var page = 1;
    
    for (var i in this.records)
    {
        var data = this.records[i];
        
        var o = this.newCard(page, i, x, y, false);
        
        this.doc.setFontSize(5.0);
        this.doc.setFontType("bold");

        page = o.page;
        x = o.x;
        y = o.y;

        var x1 = x + 10;
        var y1 = y + 10;
        var w = this.options.card.width - 60;
        var h = this.options.card.height - 60;
        
        this.addImage(data['barcode'], "PNG", x1 + 10, y1 + 10, w, h);
    }

    this.onComplete();
    //this.doc.output('save', 'MRP_label.pdf');
}