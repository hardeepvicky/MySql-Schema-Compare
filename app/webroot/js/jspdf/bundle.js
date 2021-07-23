jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var page = 1;
    var column_1_width = this.options.page.width * 0.60 ;
    var column_2_width = this.options.page.width * 0.35 ;

    for (var i in this.records)
    {
        var data = this.records[i];
        var o = this.newCard(page, i, x, y, false);

        this.doc.setFontSize(6.5);
        this.doc.setFontType("bold");

        page = o.page;
        x = o.x;
        y = o.y;

        var x1 = x + 10;
        var y1 = y + 10;
        
        this.myText("STYLE CODE : " + data['style'], {}, x1, y1, column_1_width);

        y1 += 40;
        this.myText("SIZE : " + data['size'] + ", Qty : " + data["qty"], {}, x1, y1, column_1_width);

        y1 += 40;
        this.myText("COLOR : " + data['color'], {}, x1, y1, column_1_width);
        
        y1 += 40;
        this.myText("SKU : " + data['sku'], {}, x1, y1, column_1_width);
        
        y1 += 20;
        var rect_w = 450, rect_h = 320, qr_w = 260;
        if (typeof data["cutter_master_qrcode"] != "undefined")
        {
            var x3 = x + (rect_w / 2) - (qr_w / 2);
            var x2 = x1 + rect_w / 2;
            this.rect(x1, y1, rect_w, rect_h);
            this.myText("Cutter Master", { align : "center" }, x2, y1 + 25, rect_w);
            this.addImage(data['cutter_master_qrcode'], "PNG", x3, y1 + 30, qr_w, qr_w);
            this.myText(data["cutter_master"], { align : "center" }, x2, y1 + qr_w + 50, rect_w);
        }
        
        var w = column_2_width + 50
        var x1 = this.options.card.width - w;
        y1 = y;
        this.rect(x1, y1, column_2_width, w);
        this.addImage(data['qrcode'], "PNG", x1 + 10, y1 + 10, column_2_width - 20, column_2_width - 20);

        y1 = y1 + w - 22;
        this.doc.setFontSize(9);
        x1 = this.options.card.width - (w / 2);
        this.myText(data['barcode'], { align : "center"}, x1, y1, w);

        this.doc.setFontSize(4.1);
        y1 = y + w + 20;

        this.doc.setFontType("normal");
        this.myText("Above QR Code is for Internal use only", { align : "center"}, x1, y1, w);
        
        y1 = w + 90;
        x1 = x + 10;
        
        this.line(x, y1, this.options.page.width, y1);
        this.doc.setFontSize(6.0);
        this.doc.setFontType("bold");
        
        y1 += 30;
        this.myText("Talior", {}, x1, y1, column_1_width);
        
        y1 += 300;
        this.line(x, y1, this.options.page.width, y1);
        y1 += 30;
        this.myText("Overlock", {}, x1, y1, column_1_width);
        
        y1 += 300;
        this.line(x, y1, this.options.page.width, y1);
        y1 += 30;
        this.myText("Folding", {}, x1, y1, column_1_width);
        
        y1 += 300;
        this.line(x, y1, this.options.page.width, y1);
        y1 += 30;
        this.myText("Others", {}, x1, y1, column_1_width);
    }

    this.onComplete();
    //this.doc.output('save', 'MRP_label.pdf');
}