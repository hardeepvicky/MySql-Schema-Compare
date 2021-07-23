jspdf_extend.load = function()
{
    toDataUrl("/img/barcode_label_sevenrock.jpg", "image/jpeg", function(src, base)
    {
        jspdf_extend.label = base;
        jspdf_extend.create();
    });
}

jspdf_extend.create = function()
{
    this.doc.setFont('Arial');
    this.doc.setFontSize(6.3);

    var x = this.options.page.margin.left;
    var y = this.options.page.margin.top;

    var page = 1;
    var old_batch_bundle_id = false;

    for (var i in this.records)
    {
        var data = this.records[i];
        
        if (old_batch_bundle_id != data["batch_bundle_id"] && old_batch_bundle_id !== false)
        {
            this.newCard(page, i, x, y, false);
            this.doc.setFillColor(204, 204, 204);
            this.rect(x + 1, y + 1, this.w, this.h - this.options.page.margin.top, 'F');
        }
        
        old_batch_bundle_id = data["batch_bundle_id"];
        
        var o = this.newCard(page, i, x, y, false);

        page = o.page;
        x = o.x;
        y = o.y;

        var x1 = x + 20;
        var y1 = y + 50;

        this.addImage(jspdf_extend.label, "JPEG", x1, y1, this.options.card.width, 150);

        this.doc.setFontSize(13);
        this.doc.setFontType("bold");

        var xc = x + 15 + (this.options.card.width / 2);
        y1 = y + 50 + 150 + 100;
        this.myText(data['size'], {align: "center"}, xc, y1, this.options.page.width);

        this.doc.setFontSize(8);
        this.doc.setFontType("normal");

        y1 += 80;
        this.myText(data['sku'], {align: "center"}, xc, y1, this.options.page.width);

        y1 += 50;
        this.myText(data['batch_bundle_barcode'], {align: "center"}, xc, y1, this.options.page.width);

        y1 += 200;
        this.addImage(data['barcode'], "JPEG", x1 - 10, y1 + 10, 230, 230);
        
        this.doc.setFontSize(9);
        y1 += 300;
        this.myText(data['code'], {align: "center"}, (x1 + 115), y1, this.options.page.width);

        y1 += 200;
        this.myText("<------------->", {align: "center"}, xc, y1, this.options.page.width);

    }

    this.onComplete();
}
            