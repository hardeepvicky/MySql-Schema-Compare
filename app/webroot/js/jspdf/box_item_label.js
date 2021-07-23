jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');
    this.doc.setFontSize(6.3); 
    
    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var page = 1;
    var column_1_width = this.options.page.width * 0.50 ;
    var column_2_width = this.options.page.width * 0.45 ;
    
    this.doc.setFontSize(6.0);
    this.doc.setFontType("bold");
        
    for (var i in this.records)
    {
        var data = this.records[i];
        
        var o = this.newCard(page, i, x, y, false);

        page = o.page;
        x = o.x;
        y = o.y;

        var x1 = x + 10;
        var y1 = y + 30;

        this.myText("Gate Pass No.: " + data['gate_pass_no'], {}, x1, y1, column_1_width);
        
        y1 += 35;
        var o = this.paragraph("From: " + data["from"], {}, x1, y1, column_1_width, 30, 3);
        
        y1 = o.y + 30;
        this.myText("---------------------------------------------", {}, x1, y1, column_1_width);
        
        y1 += 30;
        var o = this.paragraph("To: " + data["to"], {}, x1, y1, column_1_width, 30, 3);
        
        y1 = o.y + 30;
        this.myText("---------------------------------------------", {}, x1, y1, column_1_width);
        
        y1 += 30;
        this.myText("Qty: " + data['qty'], {}, x1, y1, column_1_width);

        y1 += 35;
        this.myText("Weight: " + data['weight'] + " K.g.", {}, x1, y1, column_1_width);
        
        y1 += 35;
        this.myText("Pack By: " + data['created_by'], {}, x1, y1, column_1_width);
        
        y1 += 35;
        this.myText("Pack On: " + data['created'], {}, x1, y1, column_1_width);
        
        var x1 = x + column_1_width + 10;
        y1 = y;
        var h = column_2_width + 30;
        this.rect(x1, y1, column_2_width, h);
        this.addImage(data['qrcode'], "PNG", x1 + 20, y1 + 20, column_2_width - 40, column_2_width - 40);

        y1 = y1 + h - 20;
        x1 = x + column_1_width + 10 + (column_2_width / 2);
        this.myText(data['box_no'], { align : "center"}, x1, y1, column_2_width);
    }

    this.onComplete();
}