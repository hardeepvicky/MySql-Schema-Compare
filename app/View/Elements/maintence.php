<style>
    div.coming_soon
    {
        margin-top : -45px;
        position: relative;
        height: 100vh;
        width : 100%;
        color : #FFF;        
    }
    
    div.coming_soon h1
    {
        font-family: arial;
        text-transform: uppercase;
        font-weight: bold;
        letter-spacing: 10px;
        overflow: hidden;
        white-space: nowrap;        
    
        position: absolute;
        font-size: 8em;        
        top : 40%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-image: url("/img/color.jpg");
        color:transparent;
        background-clip : text;
        -webkit-background-clip: text;
        -moz-background-clip: text;
        
        animation: animate 8s linear infinite;
    }
    
    div.coming_soon .details
    {
        font-family: arial;
        position: absolute;
        font-size: 1.5em;        
        top : 60%;
        left: 50%;
        transform: translate(-50%, -0%);
        color : #36c6d3;
        text-align: center;
    }
    
     @keyframes animate{
        0%
        {
            background-position : 0 0;
        }
        12%
        {
            background-position : 200px -100px;
        }
        25%
        {
            background-position : 400px -200px;
        }
        37%
        {
            background-position : 200px -300px;
        }
        50%
        {
            background-position : 0px -400px;
        }
        62%
        {
            background-position : -200px -300px;
        }
        75%
        {
            background-position : -400px -200px;
        }
        88%
        {
            background-position : -200px -100px;
        }     
        100%
        {
            background-position : -1px -1px;
        }
    }
    
    @media only screen and (max-width : 1200px) {
        div.coming_soon h1
        {
            font-size: 6em;
        }
    }
    
    @media only screen and (max-width : 979px) {
        div.coming_soon h1
        {
            font-size: 5em;
        }
    }

    @media only screen and (max-width : 767px) {
        div.coming_soon h1
        {
            font-size: 3em;
        }
    }

    @media only screen and (max-width : 480px) {
        div.coming_soon h1
        {
            font-size: 2em;
        }
    }

    @media only screen and (max-width : 320px) {
        div.coming_soon h1
        {
            font-size: 1em;
        }
    }
    
    
   
</style>

<div class="coming_soon">
    <h1>Maintenance</h1>    
    <h5 class="details">Site Under Construction</h5>
</div>
