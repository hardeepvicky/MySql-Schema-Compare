var jspdf_extend = {
    w : 0, 
    h : 0,
    row : 0,
    page : 1,
    SF : 0.18,    
    doc : {},
    data : {},
    
    //functions and events
    load : function (){
        console.error("Function Body not Declared yet");
    },
    create : function (){
        console.error("Function Body not Declared yet");
    },
    onComplete : function (){
        console.error("Function Body not Declared yet");
    },
    
    generate : function (doc, options)
    {
        this.doc = doc;
        this.options = options;
        
        this.w = options.card.width;
        this.h = options.card.height;
        this.row = 0;        
        
        if (typeof options.onComplete !== "undefined")
        {
            this.onComplete = options.onComplete;
        }
        
        this.load();
    },
    _pv : function (v)
    {
        return v * this.SF;
    },
    rect : function (x, y, w, h, style) {
        this.doc.rect(this._pv(x), this._pv(y), this._pv(w), this._pv(h), style);
    },
    roundedRect : function (x, y, w, h, rx, ry, style) {
        this.doc.roundedRect(this._pv(x), this._pv(y), this._pv(w), this._pv(h), this._pv(rx), this._pv(ry), style);
    },
    line : function (x1, y1, x2, y2) {
        this.doc.line(this._pv(x1), this._pv(y1), this._pv(x2), this._pv(y2));
    },
    addImage : function (image, type, x1, y1, x2, y2)
    {
        this.doc.addImage(image, type, this._pv(x1), this._pv(y1), this._pv(x2), this._pv(y2));  
    },
    text : function (x, y, txt)
    {
        this.doc.text(this._pv(x), this._pv(y), txt);
    },
    myText : function (txt, opt, x, y, w)
    {
        var txt_w = this.doc.myText(txt, opt, this._pv(x), this._pv(y), this._pv(w));
        
        return (txt_w / this.SF);
    },
    paragraph : function(txt, options, x, y, w, lh, line_limit)     
    {
        var o = this.doc.paragraph(txt, options, this._pv(x), this._pv(y), this._pv(w), this._pv(lh), line_limit);
        
        return { "y" : (o.y / this.SF)};
    },
    newCard : function (page, i, x, y, set_border)
    {
        if (i > 0)
        {
            x += this.w + this.options.card.hSpace;
        }

        if (i > 0 && i % this.options.page.cols === 0)
        {
            y += this.h + this.options.card.vSpace;
            x = this.options.page.margin.left;
            this.row++;
        } 

        if (this.row > 0 && this.row % this.options.page.rows === 0)
        {
            this.row = 0;
            x = this.options.page.margin.left;
            y = this.options.page.margin.top;
            this.doc.addPage();
            
            page++;
        }
        
        if (set_border)
        {
            var bg = this.options.card.bg;
            this.doc.setDrawColor(0, 0, 0);
            this.doc.setLineWidth(0.5);
            this.doc.setFillColor(bg.r, bg.g, bg.b);
            this.rect(x, y, this.w, this.h, 'FD');
        }
        
        return { "x" : x, "y" : y, "page" : page};
    },
    addBox : function(x1, y1, cw, ch, bw, bh, opt)
    {
        var x = x1, y = y1;
        
        if (typeof opt.align != "undefined")
        {
            if (opt.align == "center")
            {
                x = x + cw / 2 - bw / 2;
            }
            if (opt.align == "right")
            {
                x = x + cw - bw;
            }
        }
        
        if (typeof opt.vertical_align != "undefined")
        {
            if (opt.vertical_align = "center")
            {
                y = y + ch / 2 - bh / 2;
            }
            if (opt.vertical_align = "bottom")
            {
                y = y + ch - bh;
            }
        }
        
        this.rect(x, y, bw, bh, 'F');
        
        return {"x" : x, "y" : y, "x2" : x + bw, "y2" : y + bh};
    }
};