jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');
    this.doc.setFontSize(6.3); 
    
    var addBlankPage = typeof this.options.addBlankPage == "undefined" ? true : this.options.addBlankPage;

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var page = 1;
    var column_1_width = this.options.page.width * 0.55 ;
    var column_2_width = this.options.page.width * 0.37 ;
    var old_batch_bundle_id = false;
    
    for (var i in this.records)
    {
        var data = this.records[i];
        
        if (addBlankPage && old_batch_bundle_id != data["batch_bundle_id"] && old_batch_bundle_id !== false)
        {
            this.newCard(page, i, x, y, false);
            this.doc.setFontSize(30);
            this.myText("BLANK", { align : "center"}, this.options.card.width / 2, this.options.card.height / 2, this.options.card.width);
        }
        
        old_batch_bundle_id = data["batch_bundle_id"];
        
        var o = this.newCard(page, i, x, y, false);
        
        this.doc.setFontSize(5.0);
        this.doc.setFontType("bold");

        page = o.page;
        x = o.x;
        y = o.y;

        var x1 = x + 10;
        var y1 = y - 10;

        this.myText("BRAND : " + data['brand'], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("COLOR : " + data['color'], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("STYLE CODE : " + data['style'], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("SIZE : " + data['size'], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("NET QUANTITY : 01 N", {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("MRP Rs. " + data['price'] +  " (Incl. all taxes)", {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("GENERIC NAME : " + data["art_name"], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("DIMENSIONS (in cms) : " + data['dimension'], {}, x1, y1, column_1_width);

        y1 += 30;
        this.myText("MO./YR OF MANUFACTURE : " + data['manufacture_year_month'], {},  x1, y1, column_1_width);

        y1 += 30;
        var o = this.paragraph("MFD. BY / MARKETED BY / CUSTOMER CARE: " + data["address"], {}, x1, y1, column_1_width, 30, 5);

        y1 = o.y + 30;
        var o = this.paragraph("CUSTOMER CARE : " + data['customer_care_no'] + ", " + data['customer_care_email'], {}, x1, y1, column_1_width, 30, 5);

        var x1 = x + column_1_width + 10;
        y1 = y;
        var h = column_2_width + 50;
        this.rect(x1, y1, column_2_width, h);
        this.addImage(data['qrcode'], "PNG", x1 + 20, y1 + 20, column_2_width - 40, column_2_width - 40);

        y1 = y1 + h - 22;
        this.doc.setFontSize(9);
        x1 = x + column_1_width + 10 + (column_2_width / 2);
        this.myText(data['serial_code'], { align : "center"}, x1, y1, column_2_width);

        this.doc.setFontSize(4.1);
        x1 = x + column_1_width + 10 + (column_2_width / 2);
        y1 = y + h + 20;

        this.doc.setFontType("normal");
        this.myText("Above QR Code is for Internal use only", { align : "center"}, x1, y1, column_2_width);
        
    }

    this.onComplete();
    //this.doc.output('save', 'MRP_label.pdf');
}