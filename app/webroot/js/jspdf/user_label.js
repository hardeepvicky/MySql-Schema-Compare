jspdf_extend.load = function ()
{
    this.doc.setFont('Arial');

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var column_1_width = this.options.card.width * 0.60 ;
    var column_2_width = this.options.card.width * 0.35 ;
    
    this.doc.setFontType("bold");
    
    var page = 1;
    var i = 0;
    while( i < this.records.length )
    {
        var o = this.newCard(page, i, x, y, false);
        y = o.y + 30;
        for (var a = 0; a < 2 && i < this.records.length; a++)
        {
            x = (this.options.card.width * a + (a * 20));
    
            var data = this.records[i];

            var x1 = x + 10;
            var y1 = y;

            this.doc.setFontSize(5.0);
            this.myText(data['user_name'], {}, x1, y1, column_1_width);

            y1 += 30;
            this.myText("DEPT.: " + data['dept'], {}, x1, y1, column_1_width);

            y1 += 30;
            this.myText("DESG.: " + data['desg'], {}, x1, y1, column_1_width);
            
            this.doc.setFontSize(8.0);
            y1 += 45;
            this.myText(data['user_id'], {}, x1, y1, column_1_width);

            var x1 = x + column_1_width + 5;
            y1 = y - 20;
            this.addImage(data['qrcode'], "PNG", x1, y1, column_2_width + 15, column_2_width + 15);
            i++;
        }
        
        page++;
    }
    
    this.onComplete();
}