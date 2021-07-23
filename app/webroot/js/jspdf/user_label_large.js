jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var column_1_width = this.options.card.width * 0.65 ;
    
    var page = 1;
    var i = 0;
    while( i < this.records.length )
    {
        var o = this.newCard(page, i, x, y, false);
        y = o.y + 30;
        x = o.x;
        page = o.page;

        var data = this.records[i];

        var x1 = x + 20;
        var y1 = y;

        this.doc.setFontSize(7.0);
        
        this.myText(data['user_name'], {}, x1, y1, column_1_width);

        y1 += 40;
        this.myText("DEPT.: " + data['dept'], {}, x1, y1, column_1_width);

        y1 += 40;
        this.myText("DESG.: " + data['desg'], {}, x1, y1, column_1_width);
        
        this.doc.setFontSize(13);
        y1 += 60;
        this.myText(data['user_id'], {}, x1, y1, column_1_width);

        var x1 = x + column_1_width + 5;
        y1 = o.y - 15;
        this.addImage(data['qrcode'], "PNG", x1, y1, 255, 255);
        i++;
    }
    
    this.onComplete();
}