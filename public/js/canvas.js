var c=document.getElementById("logoCanvas");
var ctx=c.getContext("2d");
ctx.font="30px Georgia";
ctx.textAlign = 'center';
ctx.textBaseline = 'middle';
var halfWidth = c.width/2;
var halfHeight = c.height/2;
ctx.strokeText("Jogo do Refresco",halfWidth,halfHeight);
